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

        $orderItems = $this->getDoctrine()->getRepository(Cart::class)
            ->findByUser($user->getId());

        foreach($orderItems as $item) {
            $item->setOrder($order);
            $entityManager->persist($item);
        }

        $entityManager->flush();

        return $this->redirectToRoute('order.list');
    }
}