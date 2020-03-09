<?php

namespace codejunk\ecommerce\cart;

/**
 * Class ItemDecorator - a cart item wrapper
 * notifies parent cart object about significant state changes
 *
 * @package codejunk\ecommerce\cart
 */
class ItemDecorator implements ItemInterface
{
    /**
     * @var ItemInterface
     */
    protected $item;

    /**
     * @var \SplSubject
     */
    protected $subject;

    /**
     * @var DiscountCollectionInterface
     */
    protected $discounts;



    /**
     * ItemDecorator constructor.
     * @param ItemInterface $item
     * @param DiscountCollectionInterface $discounts
     * @param \SplObserver $observer
     */
    public function __construct(
        ItemInterface $item,
        DiscountCollectionInterface $discounts,
        \SplObserver $observer
    ) {
        $this->item = $item;
        $this->subject = new EventSubject();
        $this->subject->attach($observer);
        $this->discounts = $discounts;
    }

    /**
     * Redirects calls to decorated object
     *
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->item, $name], $arguments);
    }


    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->item->getId();
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->item->getTitle();
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        $price = $this->item->getPrice();
        $discount = 0;
        // Apply matching discount if any
        foreach ($this->discounts->getList($this->item) as $discountOption) {
            $discount += ($discountOption->getValue($this->item) / $this->item->getQuantity());
        }
        return round($price - $discount, 2);
    }

    /**
     * @return float
     */
    public function getOriginalPrice(): float
    {
        return $this->item->getOriginalPrice();
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity): void
    {
        $this->item->setQuantity($quantity);
        $this->subject->notify(EventItemQuantityChange::class, $this);
        $this->subject->notify(EventCartChange::class, $this);
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->item->getQuantity();
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getOption(string $name)
    {
        return $this->item->getOption($name);
    }

    /**
     * @param string $name
     * @param $value
     */
    public function setOption(string $name, $value): void
    {
        $this->item->setOption($name, $value);
    }

}
