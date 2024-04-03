<?php

namespace App\Controller\ApiController;

use App\Repository\CartRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/api/", name="app_api_")
 */
class CartController extends AbstractController {

    /**
     * @Route("cart", name="cart", methods={"GET"})
     */
    public function cartJson(CartRepository $cartRepository): jsonResponse
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
}