<?php

namespace Dblencowe\SalesCalc\Model;

use Tightenco\Collect\Support\Collection;

class Order
{
    private $items;

    public function __construct()
    {
        $this->items = new Collection();
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(OrderItem $item)
    {
        $this->items->add($item);

        return $this;
    }
}