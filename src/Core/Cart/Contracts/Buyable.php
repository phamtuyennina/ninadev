<?php

namespace NINA\Core\Cart\Contracts;

interface Buyable
{
    public function getBuyableIdentifier($options = null);
    public function getBuyableDescription($options = null);
    public function getBuyablePrice($options = null);
    public function getBuyableWeight($options = null);
}