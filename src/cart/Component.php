<?php
namespace Cart;

abstract class Component implements ComponentInterface
{
    protected $id;

    protected $title;

    protected $value = 0;


    /**
     * Component constructor.
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

    public function getOption(string $option)
    {
        if (property_exists($this, $option)) {
            return $this->$option;
        }
    }
}