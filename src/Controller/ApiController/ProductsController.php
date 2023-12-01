<?php

namespace App\Controller\ApiController;

use App\Repository\CategoryRepository;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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
     * @Route("CategoryList", name="category_list", methods={"GET"})
     */
    public function categoryListJson(CategoryRepository $categoryRepository)
    {
        $categoryList = $categoryRepository->findAll();
        return $this->json(
            $categoryList,
            200,
            [],
            ['groups' => 'get_category']
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
     /**
     * @Route("categorie/productList", name="categorie_productList", methods={"GET","POST"})
     */
    public function getProductFromCategory(Request $request, ProductsRepository $productsRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        // Recovery of json informations sent by the front
        $data = json_decode($request->getContent(), true);
        
        // Check error reading Json
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new BadRequestHttpException('json invalide');
        }
        // Check if the category name exists
        if (!(isset($data['category']) && isset($data['category'][0]['name']))) {
            throw new BadRequestHttpException('json need catégory nodes with name');
        }

        // Only use the name in the array $data
        /** @var string[] $categoryNames */
        $categoryNames = array_map(
            fn(array $category) => $category['name'],
            $data['category']
        );

        // Find product containing the category names
        $product = $productsRepository->findByCategoryName($categoryNames, $entityManager);

        // Return Json of all product for one or many category to the front
        return $this->json(
            // All products of the category convert in Json
            $product,
            // The status code 200
            JsonResponse::HTTP_OK,
            // The header
            [],
            // Element group for product
            [
                'groups' => [
                    'get_products',
                ],
            ]
        );
    }

    /**
     * @Route("StockProductsList", name="stock_product", methods={"GET"})
     */
    public function stockProductsJson(ProductsRepository $productsRepository): Response
    {
        $stockProductsList = $productsRepository->findBy(['stock'=> true]);
        return $this->json(
            $stockProductsList,
            200,
            [],
            ['groups' => 'get_products']
        );
    }
}