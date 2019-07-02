<?php

namespace Dblencowe\SalesCalc;

interface BasketInterface
{
    public function add(string $sku): self;

    public function total(): int;
}