<?php

namespace App\Controller\ApiController;

use App\Entity\Orders;
use App\Entity\Orderdetails;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
* @Route("/api/commandes", name="api_orders_")
*/
class OrdersController extends AbstractController
{
    
    /**
    * @Route("/ajout", name="add")
    */
    public function add(SessionInterface $session, ProductsRepository $productsRepository, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $panier = $session->get('panier', []);

        if ($panier === []) {
            $this->addFlash('message', 'Panier vide');
        }

        $order = new Orders();

        $order->setUsers($this->getUser());
        $order->setReference(uniqid());

        foreach ($panier as $item => $quantity) {
            $orderDetails = new Orderdetails();

            $product = $productsRepository->find($item);

            $price = $product->getPrice();

            $orderDetails->setProducts($product);
            $orderDetails->setPrice($price);
            $orderDetails->setQuantity($quantity);

            $order->addOrderDetail($orderDetails);
        }

        $em->persist($order);
        $em->flush();

        return $this->json(
            $order,
            200,
            [],
            ['groups' => 'get_order']
        );
        $session->remove('panier');
    }
    
}