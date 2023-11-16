<?php

namespace App\Controller\ApiController;

use App\Entity\Orders;
use App\Entity\OrderDetails;
use App\Repository\OrdersRepository;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/api/commandes", name="app_api_commandes")
 */
class OrdersController extends AbstractController{

    // /**
    //  * @Route("/add/{id}", name:"add")
    //  */
    // public function add(SessionInterface $session, ProductsRepository $productsRepository, EntityManagerInterface $em): Response
    // {
    //     $this->denyAccessUnlessGranted('ROLE_USER');

    //     $panier = $session->get('panier', []);
    //     $id = $productsRepository->getId();
    //     $order = new Orders();

    //     $order->setUsers($this->getUser());

    //     if(empty($panier[$id])){
    //         $panier[$id] = 1;
    //     }else{
    //         $panier[$id]++;
    //     }

    //     foreach($panier as $item=> $quantity){
    //         $orderDetails = new OrderDetails();
        

    //     $product = $productsRepository->find($item);
    //     $price = $product->getPrice();

    //     $orderDetails->setProducts($product);
    //     $orderDetails->setPrice($price);
    //     $orderDetails->setQuantity($quantity);
    //     $order->addOrderDetail($orderDetails);
    //     }

    //     $em->persist($order);
    //     $em->flush();


    //     return $this->json(
    //         // All recipes of the ingredient convert in Json
    //         $order,
    //         // The status code 200
    //         JsonResponse::HTTP_OK,
    //         // The header
    //         [],
    //         // Element group for recipe
    //         [
    //             'groups' => [
    //                 'get_order',
    //             ],
    //         ]
    //     );
    // }
    // /**
    //  * @Route("/remove/{id}", name:"remove")
    //  */
    // public function remove(SessionInterface $session,$id, ProductsRepository $productsRepository,OrdersRepository $ordersRepository, EntityManagerInterface $em)
    // {
    // $this->denyAccessUnlessGranted('ROLE_USER');

    // $product = $productsRepository->find($id);
    // $order = $ordersRepository->findOneBy(['user' => $this->getUser()]);

    // if (!$order || !$product) {
    //     $response = [
    //         'success' => false,
    //         'message' => 'Le produit ou le panier n\'existe pas.',
    //     ];

    //     return new JsonResponse($response, JsonResponse::HTTP_NOT_FOUND);
    // }

    // $em->persist($order);
    // $em->flush();

    // return $this->json(
    //     // All recipes of the ingredient convert in Json
    //     $order,
    //     // The status code 200
    //     JsonResponse::HTTP_OK,
    //     // The header
    //     [],
    //     // Element group for recipe
    //     [
    //         'groups' => [
    //             'get_order',
    //         ],
    //     ]
    // );
    // }
}
