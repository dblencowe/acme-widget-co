<?php

namespace Dblencowe\SalesCalcTests;

use Dblencowe\SalesCalc\Model\Order;
use Dblencowe\SalesCalc\Model\OrderItem;
use Tightenco\Collect\Support\Collection;

class OrderModelTest extends BaseTest
{
    private $orderTestData;

    public function setUp(): void
    {
        $this->class = new Order();
        parent::setUp();
    }

    public function testCanGetItems()
    {
        $this->assertTrue(method_exists($this->class, 'getItems'));
        $this->assertInstanceOf(Collection::class, $this->class->getItems());
    }

    public function testCanAddItems()
    {
        $orderItem = new OrderItem("test", 5000);
        $this->class->addItem($orderItem);
        $this->assertTrue($this->class->getItems()->contains($orderItem));
    }
}