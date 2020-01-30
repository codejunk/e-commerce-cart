<?php

namespace Cart;


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
     * ItemDecorator constructor.
     * @param ItemInterface $item
     * @param \SplObserver $observer
     */
    public function __construct(ItemInterface $item, \SplObserver $observer)
    {
        $this->item = $item;
        $this->subject = new Subject();
        $this->subject->attach($observer);
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
        return $this->item->getPrice();
    }

    public function getOriginalPrice(): float
    {
        return $this->item->getOriginalPrice();
    }

    public function getWeight(): float
    {
        return $this->item->getWeight();
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