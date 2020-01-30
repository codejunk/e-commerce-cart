<?php
namespace Cart;

class Item implements ItemInterface
{
    /**
     * @var string
     */
    protected $id = '';
    /**
     * @var string
     */
    protected $title = '';
    /**
     * @var float
     */
    protected $price = 0.00;
    /**
     * @var float
     */
    protected $weight = 0.00;
    /**
     * @var int
     */
    protected $quantity = 0;

    /**
     * Item constructor.
     *
     * @param string $id
     * @param string $title
     * @param float $price
     * @param float $weight
     * @param int $quantity
     */
    public function __construct(string $id, string $title, float $price, float $weight, int $quantity = 1)
    {
        $this->id = $id;
        $this->title = $title;
        $this->price = $price;
        $this->weight = $weight;
        $this->quantity = $quantity;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }


    /**
     * @return float
     */
    public function getOriginalPrice(): float
    {
        return $this->price;
    }


    /**
     * @return float
     */
    public function getWeight(): float
    {
        return $this->weight;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity): void
    {
        if ($quantity < 0)
            throw new \InvalidArgumentException('Unable to set quantity ' . $quantity);
        $this->quantity = $quantity;
    }
}