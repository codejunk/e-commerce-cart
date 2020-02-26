<?php
namespace codejunk\ecommerce\cart;

class EventCartChange implements EventInterface
{
    protected $repository;

    public function handle(CartInterface $cart = null, $evenData = null): void
    {
        if ($this->repository === null) {
            $this->repository = new CartRepositorySession();
        }
        $this->repository->save($cart);
    }
}
