<?php
namespace codejunk\ecommerce\cart;

interface ComponentCollectionInterface
{
    public function get(string $id): ?ComponentInterface;

    public function add(ComponentInterface $component);

    public function getList(): array;

    public function clear(): void;
}
