<?php

namespace App\Service;

use Stripe\Charge;
use Stripe\Stripe;
use App\Entity\Cart;

class StripePayment
{
    private $stripeSecretKey;

    public function __construct(string $stripeSecretKey)
    {
        $this->stripeSecretKey = $stripeSecretKey;

    }

    public function processPayment(float $amount, string $currency)
    {
        
        Stripe::setApiKey($this->stripeSecretKey);

        try {
            $charge = Charge::create([
                'amount' => $amount * 100, 
                'currency' => Cart::DEVISE,
                'payment_method_types' => ['card'],
                'description' => 'Paiement via Stripe'
            ]);

            return $charge;
        } catch (\Stripe\Exception\CardException $e) {
            
            return $e->getMessage('Erreur de carte');
        }
    }
}