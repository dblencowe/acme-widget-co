<?php

use Dblencowe\SalesCalc\Basket;

class BasketTest extends \Dblencowe\SalesCalcTests\BaseTest
{
    private $catalogue;
    private $delivery;
    private $offers;

    public function setUp(): void
    {
        parent::setUp();
        $this->catalogue = json_decode(file_get_contents(__DIR__ . '/fixtures/catalogue.json'));
        $this->delivery = json_decode(file_get_contents(__DIR__ . '/fixtures/delivery.json'));
        $this->offers = json_decode(file_get_contents(__DIR__ . '/fixtures/offers.json'));
        $this->class = new Basket($this->catalogue, $this->delivery, $this->offers);
    }


    public function testCanInstantiateClassTest()
    {
        $this->class = new Basket($this->catalogue, $this->delivery, $this->offers);
        $this->assertInstanceOf(Basket::class, $this->class);
        $this->assertEquals($this->catalogue, $this->class->getCatalogue());
        $this->assertEquals($this->delivery, $this->class->getDelivery());
        $this->assertEquals($this->offers, $this->class->getOffers());
    }

    public function testItImplementsBasketInterface()
    {
        $this->assertTrue(in_array('Dblencowe\\SalesCalc\\BasketInterface', class_implements(get_class($this->class))));
    }

    public function testCanAddProduct()
    {
        $this->assertTrue(method_exists($this->class, 'add'));

        $product = $this->catalogue->b01;
        $this->class->add($product->sku);
    }

    public function testCannotAddProductThatDoesntExist()
    {
        $this->expectException(\Dblencowe\SalesCalc\Exception\CatalogueException::class);
        $this->class->add(uniqid());
    }

    public function testCanGetTotal()
    {
        $this->assertTrue(method_exists($this->class, 'total'));

        $testOrders = json_decode(file_get_contents(__DIR__ . '/fixtures/testorders.json'));
        foreach($testOrders as $order) {
            $basket = new Basket($this->catalogue, $this->delivery, $this->offers);
            foreach($order->items as $item) {
                $basket->add($item, $this->catalogue->$item->price);
            }

            $this->assertEquals($order->expected_total, $basket->total());
        }
    }
}