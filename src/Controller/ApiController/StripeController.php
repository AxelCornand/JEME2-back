<?php

namespace App\Controller\ApiController;

use Stripe\Stripe;
use App\Service\StripePayment;
use App\Service\ProductService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api/", name="app_api_")
 */
class StripeController extends AbstractController {


    /**
     * @Route("create" , name="create" , methods={"POST"})
     */
    public function createPayment(Request $request, ProductService $productService, StripePayment $stripePayment): JsonResponse
    {

        $jsonData = $request->getContent();
        $products = json_decode($jsonData, true);

        // Vérifier si les données JSON sont valides
        if (!is_array($products) || empty($products)) {
            return new jsonResponse('Données JSON invalides', 400);
        }

        $total = $productService->getTotal($products);

        $currency = 'EUR';

        // Traiter le paiement via Stripe
        $result = $stripePayment->processPayment($total, $currency);

        // Gérer la réponse en fonction du résultat
        if ($result instanceof \Stripe\Charge) {
            // Paiement réussi
            return new jsonResponse('Paiement réussi !');
        } else {
            // Erreur de paiement
            return new jsonResponse('Erreur de paiement : ' . $result, 400);
        }
    }
}