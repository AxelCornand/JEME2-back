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
     * @Route("cart", name="cart", methods={"GET"})
     */
    public function cartJson(CartRepository $cartRepository): Response
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