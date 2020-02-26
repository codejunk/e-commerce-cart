<?php
namespace codejunk\ecommerce\cart;

interface EventInterface
{
    public function handle(): void;
}
