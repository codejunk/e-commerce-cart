<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 1/31/20
 * Time: 6:30 PM
 */

namespace codejunk\ecommerce\cart;

interface DiscountInterface
{
    public function getId(): string;

    public function getTitle(): string;

    public function getValue($object): float;

    public function isValidFor($object): bool;
}
