<?php

namespace App\Controller\ApiController;

use Stripe\Stripe;
use App\Entity\Cart;
use Stripe\PaymentIntent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api/", name="app_api_")
 */
class StripeController extends AbstractController {

    private $privateKey;

    public function __construct()
    {
        if($_ENV['APP_ENV'] === 'dev'){
            $this->privateKey = $_ENV['STRIPE_PUBLIC_KEY_TEST'];
        } else {
            $this->privateKey = $_ENV['STRIPE_PUBLIC_KEY_LIVE'];
        }
    }

    /**
     * @Route("create" , name="create" , methods={"POST"})
     */
    public function createPayment(Request $request): JsonResponse
    {

        \Stripe\Stripe::setApiKey($this->privateKey);

        $data = json_decode($request->getContent(), true);

        $products = $data['products'];

        function getTotal(array  $products): int
        {
            $total = 0;
            foreach($products as $product){
                $total += $product->getPrice();
            }
            return $total;
        }

        $payment = PaymentIntent::create([
            'amount' => $products->getPrice() * 100,
            'currency' => Cart::DEVISE,
            'payment_method_types' => ['card'],
        ]);

        return $this->json(['clientSecret' => $payment->client_secret]);
    }
}