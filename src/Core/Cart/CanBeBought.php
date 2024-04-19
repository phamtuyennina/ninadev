<?php

namespace NINA\Core\Cart;
trait CanBeBought
{
    public function getBuyableIdentifier($options = null)
    {
        return method_exists($this, 'getKey') ? $this->getKey() : $this->id;
    }
    public function getBuyableDescription($options = null)
    {
        if (($name = $this->getAttribute('name'))) {
            return $name;
        }
        if (($title = $this->getAttribute('title'))) {
            return $title;
        }
        if (($description = $this->getAttribute('description'))) {
            return $description;
        }
    }
    public function getBuyablePrice($options = null)
    {
        if (($price = $this->getAttribute('price'))) {
            return $price;
        }
    }
    public function getBuyableWeight($options = null)
    {
        if (($weight = $this->getAttribute('weight'))) {
            return $weight;
        }

        return 0;
    }
}