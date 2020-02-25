<?php

namespace codejunk\ecommerce\cart;

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


    public function getId(): string
    {
        return $this->item->getId();
    }

    public function getTitle(): string
    {
        return $this->item->getTitle();
    }

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

    public function getOriginalPrice(): float
    {
        return $this->item->getOriginalPrice();
    }

    public function setQuantity(int $quantity): void
    {
        $this->item->setQuantity($quantity);
        $this->subject->notify(EventItemQuantityChange::class, $this);
    }

    public function getQuantity(): int
    {
        return $this->item->getQuantity();
    }
}
