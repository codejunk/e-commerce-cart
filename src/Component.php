<?php
namespace codejunk\ecommerce\cart;

abstract class Component implements ComponentInterface, OptionInterface
{
    use OptionTrait;

    protected $id;

    protected $title;

    protected $value = 0;

    /**
     * Component constructor.
     * @param string $id
     * @param string $title
     * @param float $value
     * @param array $options
     */
    public function __construct(string $id, string $title, float $value = 0, array $options = [])
    {
        $this->id = $id;
        $this->title = $title;
        $this->value = $value;
        $this->options = $options;
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


    public function getValue(): float
    {
        return $this->value;
    }
}
