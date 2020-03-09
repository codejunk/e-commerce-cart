<?php
namespace codejunk\ecommerce\cart;

class Tax extends Component
{
    public function getValue(): float
    {
        /**
         * @var CartInterface $cart
         * @var float $rate
         */
        $tax = 0;
        $cart = $this->getOption('cart');
        $rate = $this->value;
        if ($cart && $rate !== null) {
            $itemsTotal = 0;
            foreach ($cart->getItems() as $item) {
                $itemsTotal += $item->getPrice() * $item->getQuantity();
            }
            $tax = $itemsTotal * $rate / 100;
            $tax = round($tax, 2);
        }

        return $tax;
    }
}
