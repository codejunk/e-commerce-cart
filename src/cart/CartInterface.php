<?php
namespace Cart;

/**
 * Interface CartInterface
 * @package Cart
 */
interface CartInterface
{
    /**
     * @param ItemInterface $item
     * @return int
     */
    public function add(ItemInterface $item): int;

    /**
     * @param string $id
     * @return int
     */
    public function remove(string $id);

    /**
     * @param string $id
     * @return ItemInterface
     */
    public function &getItem(string $id): ItemInterface;

    /**
     * @return ItemInterface[]
     */
    public function getItems(): array;

    /**
     * @return int
     */
    public function getItemsCount(): int;

    /**
     * Clears the cart
     */
    public function clear(): void;

    /**
     * @return float
     */
    public function getItemsTotal(): float;

    /**
     * @return float
     */
    public function getTotal(): float;
}