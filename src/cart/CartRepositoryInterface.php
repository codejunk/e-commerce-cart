<?php
namespace Cart;


interface CartRepositoryInterface
{
    public function load(): CartInterface;

    public function save(CartInterface $cart): bool;
}