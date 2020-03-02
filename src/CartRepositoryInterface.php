<?php
namespace codejunk\ecommerce\cart;

interface CartRepositoryInterface
{
    public function load(): ?CartInterface;

    public function save(CartInterface $cart): bool;
}
