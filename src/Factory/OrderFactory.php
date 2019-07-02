<?php

namespace Dblencowe\SalesCalc\Factory;

use Dblencowe\SalesCalc\Model\Order;
use Dblencowe\SalesCalc\Model\OrderItem;

class OrderFactory
{
    public static function makeOrder(): Order
    {
        return new Order();
    }

    public static function makeOrderItem(string $productSku, int $price): OrderItem
    {
        return new OrderItem($productSku, $price);
    }
}