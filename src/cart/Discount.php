<?php
namespace Cart;


abstract class Discount implements DiscountInterface
{
    protected $id;

    protected $title;

    /**
     * Discount constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        foreach ($config as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getValue($object): float
    {
        return 0;
    }

    public function isValidFor($object): bool
    {
        return false;
    }
}