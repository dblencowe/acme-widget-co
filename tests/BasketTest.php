<?php

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
    }


    public function testCanInstantiateClassTest()
    {
        $this->class = new \Dblencowe\SalesCalc\Basket($this->catalogue, $this->delivery, $this->offers);
        $this->assertInstanceOf(\Dblencowe\SalesCalc\Basket::class, $this->class);
    }

    public function testItImplementsBasketInterface()
    {
        $this->assertTrue(in_array("BasketInterface", class_implements(get_class($this->class))));
    }

    public function testCanAddProduct()
    {
        $this->assertTrue(method_exists($this->class, 'add'));

        $product = $this->catalogue['b01'];
        $this->class->add($product['sku']);
    }

    public function testCanGetTotal()
    {
        $this->assertTrue(method_exists($this->class, 'total'));

        $testOrders = json_decode(file_get_contents(__DIR__ . '/fixtures/testorders.json'));
        foreach($testOrders as $order) {
            $basket = new \Dblencowe\SalesCalc\Basket($this->catalogue, $this->delivery, $this->offers);
            foreach($order->items as $item) {
                $basket->add($item->sku, $this->catalogue[$item->sku]->price);
            }

            $this->assertEquals($order->expected_total, $basket->total());
        }
    }
}