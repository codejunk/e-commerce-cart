<?php
namespace codejunk\ecommerce\cart;

interface ComponentInterface extends OptionInterface
{
    public function getId(): string;

    public function getTitle(): string;

    public function getValue(): float;

    public function getDiscount(): float;
}
