<?php

namespace App\Service;

class ProductService
{

    public function getTotal(array $products): float
    {
        $total = 0;
        foreach ($products as $product) {
            $total += $product->getPrice();
        }
        return $total;
    }
}