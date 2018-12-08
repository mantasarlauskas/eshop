<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Cart;
use AppBundle\Entity\Product;
use AppBundle\Form\ProductType;
use AppBundle\Repository\CartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends Controller
{
    /**
     * @Route("/product/add", name="product.add")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addProduct(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash(
                'form_product',
                'Produktas buvo sėkmingai pridėtas'
            );

            return $this->redirectToRoute('product.list');
        }

        return $this->render('product/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/product/edit/{id}", name="product.edit")
     * @param Request $request
     * @param Product $product
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editProduct(Request $request, Product $product)
    {
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash(
                'form_product',
                'Produktas buvo sėkmingai pakeistas'
            );

            return $this->redirectToRoute('product.list');
        }

        return $this->render('product/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/product/remove/{id}", name="product.remove")
     * @param Product $product
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function removeProduct(Product $product)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($product);
        $entityManager->flush();

        $this->addFlash(
            'form_product',
            'Produktas buvo sėkmingai pašalintas'
        );

        return $this->redirectToRoute('product.list');
    }

    /**
     * @Route("/product/list", name="product.list")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listProducts()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $products = $entityManager->getRepository(Product::class)->findAll();

        return $this->render('product/list.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * @Route("/product/cart/{id}", name="product.cart", methods="GET")
     * @param Product $product
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addToCart(Product $product)
    {
        $user = $this->getUser();

        $oldCart = $this->getDoctrine()->getRepository(Cart::class)
            ->findByUserAndProduct($user->getId(), $product->getId());

        if ($oldCart) {
            $cart = $oldCart[0];
            $cart->incrementCount();
        } else {
            $cart = new Cart();
            $cart->setCount();
            $cart->setUser($user);
            $cart->setProduct($product);
            $cart->setProductTitle($product->getName());
            $cart->setProductPrice($product->getPrice());
        }

        $product->decrementCount();

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($cart);
        $entityManager->persist($product);
        $entityManager->flush();

        return $this->redirectToRoute('product.list');
    }
}

