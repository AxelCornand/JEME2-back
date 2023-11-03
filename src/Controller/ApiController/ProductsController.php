<?php

namespace App\Controller\ApiController;

use App\Repository\ProductsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api/", name="app_api_")
 */
class ProductsController extends AbstractController {

    /**
     * @Route("productsList", name="product_list", methods={"GET"})
     */
    public function productsJson(ProductsRepository $productsRepository): Response
    {
        // Search for all products in the BDD
        $productsList = $productsRepository->findAll();

        // Return Json of all products to the front
        return $this->json(
            // list of products convert in Json
            $productsList,
            // The status code 200
            200,
            // The header
            [],
            // Element group for categories
            ['groups' => 'get_products']
        );
    }

    /**
     * @Route("newProductsList", name="new_product", methods={"GET"})
     */
    public function newProductsJson(ProductsRepository $productsRepository): Response
    {
        $newProductsList = $productsRepository->findBy(['news'=> true]);
        return $this->json(
            $newProductsList,
            200,
            [],
            ['groups' => 'get_products']
        );
    }
}