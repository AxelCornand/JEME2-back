<?php

namespace App\Controller\ApiController;

use App\Repository\CartRepository;
use App\Repository\CategoryRepository;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @Route("/api/", name="app_api_")
 */
class CartController extends AbstractController {

    /**
     * @Route("cart", name="cart")
     */
    public function index(CartRepository $cartRepository): Response
    {
        $cart = $cartRepository->findOneBy(['user' => $this->getUser(), 'status' => 'active']);

        return $this->json(
            $cart,
            // The status code 200
            200,
            // The header
            [],
            // Element group for users
            ['groups' => 'get_cart']
        );
    }

    /**
     * @Route("commande/succes", name="commande_succes")
    */
    public function cartSucces()
    {
        return new Response('Commande reçu !');
    }

    /**
     * @Route("commande/cancel", name="commande_canceled")
     */
    public function cartCancel()
    {
        return new Response('Commande annulée !');
    }

     /**
     * @Route("stripe/create/session", name="stripe_create_session", methods={"POST"})
     */
    public function stripeCreateSession(CartRepository $cartRepository)
    {
        $cart = $cartRepository->findOneBy(['user' => $this->getUser(), 'status' => 'active']);

        \Stripe\Stripe::setApiKey('TODO');

        $checkout_session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $cart->getStripeLineItems(),
            'mode' => 'payment',
            'success_url' => $this->generateUrl('cart_success', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('cart_canceled', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        return new JsonResponse(['id' => $checkout_session->id]);
    }
}