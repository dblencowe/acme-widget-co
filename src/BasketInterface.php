<?php

namespace Dblencowe\SalesCalc;

interface BasketInterface
{
    public function add(string $sku): bool;

    public function total(): int;
}