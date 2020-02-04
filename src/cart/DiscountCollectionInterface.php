<?php
namespace Cart;


/**
 * Interface DiscountCollectionInterface
 * @package Cart
 */
interface DiscountCollectionInterface
{

    /**
     * @return null|string
     */
    public function getCode(): ?string;

    /**
     * @param string $code
     */
    public function setCode(string $code): void;

    /**
     * @param string $id
     * @return DiscountInterface|null
     */
    public function get(string $id): ?DiscountInterface;

    /**
     * @param DiscountInterface $discount
     * @return mixed
     */
    public function add(DiscountInterface $discount);

    /**
     * @param null $object
     * @return DiscountInterface[]
     */
    public function getList($object = null): array;

    /**
     * Clears discount collection
     */
    public function clear(): void;
}