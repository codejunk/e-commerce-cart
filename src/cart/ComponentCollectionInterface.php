<?php
namespace Cart;


interface ComponentCollectionInterface
{
    public function get(string $id): ?ComponentInterface;

    public function add(ComponentInterface $component);

    public function getList(): array;
}