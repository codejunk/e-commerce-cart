<?php
namespace Cart;


class Tax extends Component
{
    protected $rate = 0;

    /**
     * @var CartInterface
     */
    protected $cart;

    public function getValue(): float
    {
        $tax = $this->cart->getItemsTotal() * $this->rate / 100;
        $tax = round($tax, 2);

        return $tax;
    }
}