<?php
namespace codejunk\ecommerce\cart;


class DiscountTax extends Discount
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
        return ($object instanceof Tax);
    }

    protected function calculateDiscount(Tax $tax): float
    {
        $discount = $tax->getValue() * $this->rate / 100;
        $discount = round($discount, 2);
        return $discount;
    }
}