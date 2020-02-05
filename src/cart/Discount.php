<?php
namespace Cart;


abstract class Discount implements DiscountInterface, OptionInterface
{
    use OptionTrait;

    protected $id;

    protected $title;

    protected $rate;

    /**
     * Discount constructor.
     * @param string $id
     * @param string $title
     * @param float $rate
     * @param array $options
     */
    public function __construct(string $id, string $title, float $rate = 0, array $options = [])
    {
        $this->id = $id;
        $this->title = $title;
        $this->rate = $rate;
        $this->options = $options;
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