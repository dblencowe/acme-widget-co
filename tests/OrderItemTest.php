<?php

namespace Dblencowe\SalesCalcTests;

use Dblencowe\SalesCalc\Model\OrderItem;

class OrderItemTest extends BaseTest
{
    public function testCanInstantiate()
    {
        $orderItem = new OrderItem('test', 5000);
        $this->assertTrue(is_a($orderItem, OrderItem::class));
        $this->assertEquals('test', $orderItem->getProductSku());
        $this->assertEquals(5000, $orderItem->getPrice());
    }
}