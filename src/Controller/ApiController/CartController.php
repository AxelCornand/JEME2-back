<?php

namespace App\Controller\ApiController;

use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/api/", name="app_api_")
 */
class CartController extends AbstractController
{
    /**
     * @Route("panier", name="cart_index", methods={"GET"})
     */
    public function index(SessionInterface $session, ProductsRepository $productsRepository)
    {
        $panier = $session->get('panier', []);

        $panierWithData= [];

        foreach($panier as $id => $quantity){
            $panierWithData[] = [
                'product' => $productsRepository->find($id),
                'quantity' => $quantity
            ];
        }

        $total = 0.00;

        foreach($panierWithData as $item){
            $totalItem = $item['product']->getPrice() * $item['quantity'];
            $total += $totalItem;
        }

        return $this->json(
            $panierWithData,
            200,
            [],
            ['groups' => 'get_cart']
        );
    }


    /**
     *
     *@Route("panier/add/{id}", name="cart_add", methods={"GET"})
     */
    public function add($id,SessionInterface $session)
    {
        
        $panier = $session->get('panier', []);

        if(!empty($panier[$id])){
            $panier[$id]++;
        }else{
            $panier[$id] = 1;
        }
        $session->set('panier', $panier);

        return $this->json($panier);
    }

    /**
     *
     *@Route("panier/remove/{id}", name="cart_remove", methods={"GET"})
     */
    public function remove($id,SessionInterface $session)
    {
        
        $panier = $session->get('panier', []);

        if(!empty($panier[$id])){
            unset($panier[$id]);
        }

        $session->set('panier', $panier);

        return $this->json($panier);
    }

}   