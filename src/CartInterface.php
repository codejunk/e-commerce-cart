<?php
namespace codejunk\ecommerce\cart;

/**
 * Interface CartInterface
 * @package Cart
 */
interface CartInterface extends OptionInterface
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
    public function getDiscountTotal(): float;

    /**
     * @return float
     */
    public function getDiscountItems(): float;

    /**
     * @return float
     */
    public function getDiscountComponents(): float;

    /**
     * @return float
     */
    public function getTotal(): float;

    /**
     * @param ComponentCollectionInterface|null $components
     * @return ComponentCollectionInterface
     */
    public function components(ComponentCollectionInterface $components = null): ComponentCollectionInterface;

    /**
     * @param DiscountCollectionInterface|null $discount
     * @return DiscountCollectionInterface
     */
    public function discount(DiscountCollectionInterface $discount = null): DiscountCollectionInterface;


    /**
     * Returns array representation of cart
     * @return array
     */
    public function toArray(): array;
}
