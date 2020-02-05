<?php
namespace Cart;


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
            $tax = $cart->getItemsTotal() * $rate / 100;
            $tax = round($tax, 2);
        }

        return $tax;
    }
}