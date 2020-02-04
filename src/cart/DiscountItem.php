<?php
namespace Cart;


class DiscountItem extends Discount
{
    protected $validItems = [];

    protected $rate = 0;

    public function getValue($object): float
    {
        if ($this->isValidFor($object)) {
            return $this->calculateItemDiscount($object);
        }
        return 0;
    }

    public function isValidFor($object): bool
    {
        return ($object instanceof ItemInterface);
    }

    protected function calculateItemDiscount(ItemInterface $item): float
    {
        $discount = $item->getOriginalPrice() * $item->getQuantity() * $this->rate / 100;
        $discount = round($discount, 2);
        return $discount;
    }
}