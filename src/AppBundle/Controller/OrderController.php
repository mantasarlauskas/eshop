<?php
/**
 * Created by PhpStorm.
 * User: mantas
 * Date: 18.12.6
 * Time: 13.48
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Cart;
use AppBundle\Entity\Orders;
use AppBundle\Entity\Product;
use AppBundle\Entity\Report;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class OrderController extends Controller
{
    /**
     * @Route("/order/list", name="order.list")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listOrders()
    {
        $user = $this->getUser();

        $orders = $this->getDoctrine()->getRepository(Orders::class)
            ->findBy(array('user' => $user->getId()));

        return $this->render('order/list.html.twig', [
            'orders' => $orders
        ]);
    }

    /**
     * @Route("/order/activate/{id}", name="order.activate")
     * @param Orders $order
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function activateOrder(Orders $order)
    {
        $user = $this->getUser();

        $order->setAdmin($user);
        $order->setIsAccepted(true);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($order);

        $log = new Report();

        $log->setUser($user);
        $log->setDateTime();
        $log->setText('Aktyvuotas užsakymas id:' . $order->getId());

        $entityManager->persist($log);

        $entityManager->flush();

        return $this->redirectToRoute('order.all');
    }

    /**
     * @Route("/order/finish/{id}", name="order.finish")
     * @param Orders $order
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function finishOrder(Orders $order)
    {
        $user = $this->getUser();

        $order->setAdmin($user);
        $order->setIsConfirmed(true);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($order);

        $log = new Report();

        $log->setUser($user);
        $log->setDateTime();
        $log->setText('Užbaigtas užsakymas id:' . $order->getId());

        $entityManager->persist($log);

        $entityManager->flush();

        return $this->redirectToRoute('order.all');
    }

    /**
     * @Route("/order/all/remove/{id}", name="order.all.remove")
     * @param Orders $order
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeFromAll(Orders $order)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($order);

        $log = new Report();

        $log->setUser($this->getUser());
        $log->setDateTime();
        $log->setText('Pašalintas užsakymas id:' . $order->getId());

        $entityManager->persist($log);


        $entityManager->flush();

        return $this->redirectToRoute('order.all');
    }

    /**
     * @Route("/order/all", name="order.all")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function allOrders()
    {
        $orders = $this->getDoctrine()->getRepository(Orders::class)
            ->findAll();

        return $this->render('order/all.html.twig', [
            'orders' => $orders
        ]);
    }

    /**
     * @Route("/order/add", name="order.add")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addOrder()
    {
        $user = $this->getUser();

        $order = new Orders();
        $order->setDateTime();
        $order->setUser($user);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($order);
        $entityManager->flush();

        $log = new Report();

        $log->setUser($user);
        $log->setDateTime();
        $log->setText('Sukurtas užsakymas id:' . $order->getId());

        $entityManager->persist($log);

        $orderItems = $this->getDoctrine()->getRepository(Cart::class)
            ->findByUser($user->getId());

        foreach($orderItems as $item) {
            $item->setOrder($order);
            $entityManager->persist($item);
        }

        $entityManager->flush();

        return $this->redirectToRoute('order.list');
    }

    /**
     * @Route("/order/remove/{id}", name="order.remove")
     * @param Orders $order
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removeOrder(Orders $order)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($order);

        $log = new Report();

        $log->setUser($this->getUser());
        $log->setDateTime();
        $log->setText('Pašalintas užsakymas id:' . $order->getId());

        $entityManager->persist($log);


        $entityManager->flush();

        return $this->redirectToRoute('order.list');
    }

    /**
     * @Route("/order/edit/{id}", name="order.edit")
     * @param Cart $cart
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeFromOrder(Cart $cart)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $order = $this->getDoctrine()->getRepository(Orders::class)->find($cart->getOrder());

        $log = new Report();

        $log->setUser($this->getUser());
        $log->setDateTime();
        $log->setText('Pakeistas užsakymas id:' . $order->getId());

        $entityManager->persist($log);

        if ($cart->getCount() > 1) {
            $cart->decrementCount();
            $entityManager->persist($cart);
        } else {
            $entityManager->remove($cart);
        }


        $product = $this->getDoctrine()->getRepository(Product::class)->find($cart->getProduct());
        $product->incrementCount();

        $entityManager->persist($product);
        $entityManager->flush();

        $products = $this->getDoctrine()->getRepository(Cart::class)
            ->findBy(array('order' => $order->getId()));
        if(count($products) == 0) {
            $entityManager->remove($order);

            $log = new Report();

            $log->setUser($this->getUser());
            $log->setDateTime();
            $log->setText('Pašalintas užsakymas id:' . $order->getId());

            $entityManager->persist($log);
            $entityManager->flush();

            return $this->redirectToRoute('order.list');
        }


        return $this->redirectToRoute('order.view', array('id' => $order->getId()));
    }

    /**
     * @Route("/order/{id}", name="order.view")
     * @param Orders $order
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewOrder(Orders $order)
    {
        $products = $this->getDoctrine()->getRepository(Cart::class)
            ->findBy(array('order' => $order->getId()));

        return $this->render('order/order.html.twig', [
            'products' => $products
        ]);
    }

}