<?php
namespace codejunk\ecommerce\cart;

class Item implements ItemInterface, OptionInterface
{
    use OptionTrait;

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
     * @var int
     */
    protected $quantity = 0;


    /**
     * Item constructor.
     *
     * @param string $id
     * @param string $title
     * @param float $price
     * @param int $quantity
     * @param array $options
     */
    public function __construct(string $id, string $title, float $price, int $quantity = 1, array $options = [])
    {
        $this->id = $id;
        $this->title = $title;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->options = $options;
    }


    /**
     * Magic method to access item options
     *
     * @param $name
     * @param $arguments
     * @return mixed|null
     */
    public function __call($name, $arguments)
    {
        if (substr($name, 0, 3) === 'get') {
            $optionName = lcfirst(substr($name, 3));
            return $this->options[$optionName] ?? null;
        }
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
        if ($quantity < 0) {
            throw new \InvalidArgumentException('Unable to set quantity ' . $quantity);
        }
        $this->quantity = $quantity;
    }
}
