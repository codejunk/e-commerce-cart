<?php
namespace Cart;


/**
 * Interface ItemInterface
 * @package Cart
 */
interface ItemInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return string
     */
    public function getTitle(): string;

    /**
     * @return float
     */
    public function getPrice(): float;

    /**
     * @return float
     */
    public function getOriginalPrice(): float;

    /**
     * @return float
     */
    public function getWeight(): float;

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity): void;

    /**
     * @return int
     */
    public function getQuantity(): int;

}