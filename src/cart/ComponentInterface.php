<?php
namespace Cart;


interface ComponentInterface
{
    public function getId(): string;

    public function getTitle(): string;

    public function getValue(): float;
}