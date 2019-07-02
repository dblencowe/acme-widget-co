<?php

namespace Dblencowe\SalesCalc;

use Dblencowe\SalesCalc\Exception\CatalogueException;
use Dblencowe\SalesCalc\Exception\OrderException;
use Dblencowe\SalesCalc\Factory\OrderFactory;
use \stdClass;

class Basket implements BasketInterface
{
    private $catalogue;
    private $delivery;
    private $offers;
    private $order;

    private $lineItemCost = 0;
    private $deliveryCost = 0;
    private $offersApplied = [];
    private $discountAmount;

    public function __construct(stdClass $catalogue, array $delivery, array $offers)
    {
        $this->catalogue = $catalogue;
        $this->delivery = $delivery;
        $this->offers = $offers;

        // Instantiate a new basket
        $this->order = OrderFactory::makeOrder();
    }

    public function add(string $sku): BasketInterface
    {
        if (!isset($this->catalogue->$sku)) {
            throw new CatalogueException($sku . ' could not be found in the supplied catalogue');
        }
        $product = $this->catalogue->$sku;
        $orderItem = OrderFactory::makeOrderItem($product->sku, $product->price);
        $this->order->addItem($orderItem);

        return $this;
    }

    public function total(): int
    {
        $total = $this->getSubTotal();
        $total = $this->checkOffers($total);
        $total = $this->calculateDelivery($total);

        return $total;
    }

    public function getCatalogue(): stdClass
    {
        return $this->catalogue;
    }

    public function getDelivery(): array
    {
        return $this->delivery;
    }

    public function getOffers(): array
    {
        return $this->offers;
    }

    private function getSubTotal(): int
    {
        $total = 0;
        foreach($this->order->getItems() as $lineItem) {
            $total += $lineItem->getPrice();
        }
        $this->lineItemCost = $total;

        return $total;
    }

    private function calculateDelivery(int $currentTotal): int
    {
        foreach($this->delivery as $shippingBand) {
            if ($currentTotal >= $shippingBand->lower_bound && $currentTotal <= $shippingBand->upper_bound) {
                $this->deliveryCost = $shippingBand->delivery_cost;

                return $currentTotal + $shippingBand->delivery_cost;
            }
        }

        throw new OrderException('Unable to find correct shipping band for total ' . $currentTotal);
    }

    private function checkOffers(int $currentTotal): int
    {
        $groupedItems = $this->order->getItems()->groupBy(function ($item, $key) {
            return $item->getProductSku();
        });
        $skus = $groupedItems->keys()->toArray();

        foreach($this->offers as $offer) {
            if (empty(array_intersect($skus, $offer->affected_skus))) {
                continue;
            }

            $applicableProducts = array_intersect($skus, $offer->affected_skus);
            foreach($applicableProducts as $sku) {
                if ($groupedItems->get($sku)->count() >= $offer->threshold) {
                    $this->offersApplied[] = $offer;

                    $discount = $this->calculateDiscount(
                        $offer->discount_type,
                        $offer->discount,
                        $groupedItems->get($sku)->first()->getPrice()
                    );
                    $this->discountAmount += $discount;
                    $currentTotal -= $discount;
                }
            }
        }

        return $currentTotal;
    }

    private function calculateDiscount(string $type, int $amount, int $subject): float
    {
        switch($type) {
            case "percent":
                return $subject * ((100 - $amount) / 100);
            case "flat":

                return $amount;
            default:
                throw new OrderException('Unable to apply discount with type ' . $type);
        }
    }
}