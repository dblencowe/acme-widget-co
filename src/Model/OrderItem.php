<?php

namespace Dblencowe\SalesCalc\Model;

class OrderItem
{
    private $productSku;
    private $price;

    public function __construct(string $productSku, int $price)
    {
        $this->productSku = $productSku;
        $this->price = $price;
    }

    public function getProductSku(): string
    {
        return $this->productSku;
    }

    public function getPrice(): int
    {
        return $this->price;
    }
}