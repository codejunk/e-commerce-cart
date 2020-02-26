<?php
namespace codejunk\ecommerce\cart;

class EventItemQuantityChange implements EventInterface
{
    public function handle(CartInterface $cart = null, ItemInterface $item = null): void
    {
        if ($item->getQuantity() == 0) {
            $cart->remove($item->getId());
        }
    }
}
