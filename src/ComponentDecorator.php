<?php
namespace codejunk\ecommerce\cart;


/**
 * Class ComponentDecorator - component wrapper class
 * contains link to parent cart object to apply additional calculations logic
 * @package codejunk\ecommerce\cart
 */
class ComponentDecorator implements ComponentInterface
{
    /**
     * @var ComponentInterface
     */
    protected $component;

    /**
     * @var CartInterface
     */
    protected $cart;

    /**
     * ComponentDecorator constructor.
     * @param ComponentInterface $component
     * @param CartInterface $cart
     */
    public function __construct(ComponentInterface $component, CartInterface $cart)
    {
        $this->component = $component;
        $this->cart = $cart;
    }


    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->component->getId();
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->component->getTitle();
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->component->getValue();
    }

    /**
     * @return float
     */
    public function getDiscount(): float
    {
        $discount = 0;
        // Apply matching discount if any
        foreach ($this->cart->discount()->getList($this->component) as $discountOption) {
            $discount += $discountOption->getValue($this->component);
        }
        return $discount;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getOption(string $name)
    {
        return $this->component->getOption($name);
    }

    /**
     * @param string $name
     * @param $value
     */
    public function setOption(string $name, $value): void
    {
        $this->component->setOption($name, $value);
    }

}