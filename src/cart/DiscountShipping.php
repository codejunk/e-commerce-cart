<?php
namespace Cart;


class DiscountShipping extends Discount
{
    protected $validItems = [];

    protected $rate = 100;

    public function getValue($object): float
    {
        if ($this->isValidFor($object)) {
            return $this->calculateDiscount($object);
        }
        return 0;
    }

    public function isValidFor($object): bool
    {
        return ($object instanceof Shipping);
    }

    protected function calculateDiscount(Shipping $shipping): float
    {
        $discount = $shipping->getValue() * $this->rate / 100;
        $discount = round($discount, 2);
        return $discount;
    }
}