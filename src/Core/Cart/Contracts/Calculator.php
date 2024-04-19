<?php
namespace NINA\Core\Cart\Contracts;
use NINA\Core\Cart\CartItem;
interface Calculator
{
    public static function getAttribute(string $attribute, CartItem $cartItem);
}