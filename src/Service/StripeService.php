<?php

namespace App\Service;

use App\Entity\Cart;
use App\Entity\Products;

class StripeService
{
    private $privateKey;

    public function __construct()
    {
        if($_ENV['APP_ENV'] === 'dev'){
            $this->privateKey = $_ENV['STRIPE_PUBLIC_KEY_TEST'];
        } else {
            $this->privateKey = $_ENV['STRIPE_PUBLIC_KEY_LIVE'];
        }
    }

    public function paymentIntent(Products $products)
    {
        \Stripe\Stripe::setApiKey($this->privateKey);

        return \Stripe\PaymentIntent::create([
            'amount' => $products->getPrice() * 100,
            'currency' => Cart::DEVISE,
            'payment_method_types' => ['card'],
        ]);
    }

    public function payment(
        $amount,
        $currency,
        $name,
        array $stripeParameter
    )
    {
        \Stripe\Stripe::setApiKey($this->privateKey);
        $payment_intent = null;

        if(isset($stripeParameter['stripeintentId'])) {
            $payment_intent = \Stripe\PaymentIntent::retrieve($stripeParameter['stripeintentId']);
        }


        return $payment_intent;
    }

    public function stripe(array $stripeParameter, Products $products)
    {
        return $this->payment(

            amount: $products->getPrice() * 100,
            currency: Cart::DEVISE,
            name: $products->getName(),
            $stripeParameter
            
        );
    }
}