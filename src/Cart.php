<?php
namespace codejunk\ecommerce\cart;

/**
 * Class Cart
 * @package codejunk\ecommerce\cart
 */
class Cart implements CartInterface
{
    use OptionTrait;

    /**
     * @var ItemInterface[]
     */
    protected $items = [];

    /**
     * @var \SplObserver
     */
    protected $observer;

    /**
     * @var |SplSubject
     */
    protected $subject;

    /**
     * @var ComponentCollection
     */
    protected $components;


    /**
     * @var DiscountCollectionInterface
     */
    protected $discounts;


    /**
     * @var CartRepositoryInterface
     */
    protected $repository;


    /**
     * @param string $eventName
     * @param $eventData
     */
    protected function trigger(string $eventName, $eventData)
    {
        $this->subject = new EventSubject();
        $this->subject->attach($this->observer);
        $this->subject->notify($eventName, $eventData);
    }

    /**
     * Cart constructor.
     * @param ComponentCollectionInterface|null $components
     * @param DiscountCollectionInterface|null $discounts
     * @param CartRepositoryInterface|null $repository
     * @param \SplObserver|null $eventObserver
     */
    public function __construct(
        CartRepositoryInterface $repository = null,
        \SplObserver $eventObserver = null,
        ComponentCollectionInterface $components = null,
        DiscountCollectionInterface $discounts = null
    ) {
        $this->repository = $repository ?? new CartRepositorySession();
        $this->observer = $eventObserver ?? new CartEventObserver($this);
        $this->components = $components ?? new ComponentCollection($this->observer);
        $this->discounts = $discounts ?? new DiscountCollection($this->observer);
    }


    /**
     * @param ItemInterface $item
     * @return int
     */
    public function add(ItemInterface $item): int
    {
        if (isset($this->items[$item->getId()])) {
            $this->items[$item->getId()]->setQuantity(
                $this->items[$item->getId()]->getQuantity() + 1
            );
        } else {
            $itemDecorator = new ItemDecorator($item, $this->discounts, $this->observer);
            $this->items[$item->getId()] = $itemDecorator;
        }

        $this->trigger(EventCartChange::class, $this);

        return $this->items[$item->getId()]->getQuantity();
    }

    /**
     * @param string $id
     * @return int|void
     */
    public function remove(string $id)
    {
        if (isset($this->items[$id])) {
            unset($this->items[$id]);
        } else {
            throw new \InvalidArgumentException('Item with id:' . $id . ' is not exists');
        }

        $this->trigger(EventCartChange::class, $this);
    }

    /**
     * @param string $id
     * @return ItemInterface
     */
    public function &getItem(string $id): ItemInterface
    {
        if (isset($this->items[$id])) {
            return ($this->items[$id]);
        } else {
            throw new \InvalidArgumentException('Item with id:' . $id . ' is not exists');
        }
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @return int
     */
    public function getItemsCount(): int
    {
        $count = 0;
        foreach ($this->items as $item) {
            $count += $item->getQuantity();
        }
        return $count;
    }

    /**
     *
     */
    public function clear(): void
    {
        $this->items = [];
        $this->components->clear();
        $this->discounts->clear();

        $this->trigger(EventCartChange::class, $this);
    }

    /**
     * @return float
     */
    public function getItemsTotal(): float
    {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item->getOriginalPrice() * $item->getQuantity();
        }
        return round($total, 2);
    }


    /**
     * @return float
     */
    public function getDiscountTotal(): float
    {
        return $this->getItemsDiscount() + $this->getComponentsDiscount();
    }


    /**
     * @return float
     */
    public function getTotal(): float
    {
        // Calculate items total
        $total = ($this->getItemsTotal() - $this->getItemsDiscount());
        // Calculate components total
        $total += ($this->getComponentsTotal() - $this->getComponentsDiscount());

        return $total;
    }

    /**
     * @param ComponentCollectionInterface|null $components
     * @return ComponentCollectionInterface
     */
    public function components(ComponentCollectionInterface $components = null): ComponentCollectionInterface
    {
        if ($components !== null) {
            $this->components = $components;
        }
        return $this->components;
    }

    /**
     * @param DiscountCollectionInterface|null $discount
     * @return DiscountCollectionInterface
     */
    public function discount(DiscountCollectionInterface $discount = null): DiscountCollectionInterface
    {
        if ($discount !== null) {
            $this->discounts = $discount;
        }
        return $this->discounts;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
       return [];
    }

    /**
     * @return float
     */
    protected function getItemsDiscount()
    {
        $discount = 0;
        foreach ($this->items as $item) {
            // Apply matching discount if any
            foreach ($this->discounts->getList($item) as $discountOption) {
                $discount += $discountOption->getValue($item);
            }
        }
        return round($discount, 2);
    }

    /**
     * @return float|int
     */
    protected function getComponentsDiscount()
    {
        $discount = 0;
        foreach ($this->components->getList() as $component) {
            // Apply matching discount if any
            foreach ($this->discounts->getList($component) as $discountOption) {
                $discount += $discountOption->getValue($component);
            }
        }
        return $discount;
    }

    /**
     * @return float|int
     */
    protected function getComponentsTotal()
    {
        $total = 0;
        foreach ($this->components->getList() as $component) {
            $total += $component->getValue();
        }

        return $total;
    }
}
