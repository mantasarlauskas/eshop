<?php
/**
 * Created by PhpStorm.
 * User: mantas
 * Date: 18.12.6
 * Time: 13.48
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Cart;
use AppBundle\Entity\Product;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CartController extends Controller
{
    /**
     * @Route("/cart/list", name="cart.list")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listCart()
    {
        $user = $this->getUser();

        $cartItems = $this->getDoctrine()->getRepository(Cart::class)
            ->findByUser($user->getId());

        return $this->render('cart/list.html.twig', [
            'cart' => $cartItems
        ]);
    }

    /**
     * @Route("/cart/remove/{id}", name="cart.remove")
     * @param Cart $cart
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeFromCart(Cart $cart)
    {
        $entityManager = $this->getDoctrine()->getManager();

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

        return $this->redirectToRoute('cart.list');
    }
}