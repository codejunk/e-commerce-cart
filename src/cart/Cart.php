<?php
namespace Cart;

class Cart implements CartInterface
{
    use OptionTrait;

    /**
     * @var ItemInterface[]
     */
    protected $items = [];

    /**
     * @var CartEventObserver
     */
    protected $observer;


    /**
     * @var ComponentCollection
     */
    protected $components;


    /**
     * @var DiscountCollectionInterface
     */
    protected $discounts;


    /**
     * Cart constructor.
     * @param ComponentCollectionInterface|null $components
     * @param DiscountCollectionInterface|null $discounts
     * @param string|null $eventObserverClass
     */
    public function __construct(
        ComponentCollectionInterface $components = null,
        DiscountCollectionInterface $discounts = null,
        string $eventObserverClass = null
    )
    {
        if ($components === null)
            $this->components = new ComponentCollection();

        if ($discounts === null)
            $this->discounts = new DiscountCollection();

        if ($eventObserverClass !== null && class_exists($eventObserverClass)) {
            $this->observer = new $eventObserverClass($this);
        } else {
            $this->observer = new CartEventObserver($this);
        }
    }


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

        return $this->items[$item->getId()]->getQuantity();
    }

    public function remove(string $id)
    {
        if (isset($this->items[$id])) {
            unset($this->items[$id]);
        } else {
            throw new \InvalidArgumentException('Item with id:' . $id . ' is not exists');
        }
    }

    public function &getItem(string $id): ItemInterface
    {
        if (isset($this->items[$id])) {
            return ($this->items[$id]);
        } else {
            throw new \InvalidArgumentException('Item with id:' . $id . ' is not exists');
        }
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getItemsCount(): int
    {
        $count = 0;
        foreach ($this->items as $item) {
            $count += $item->getQuantity();
        }
        return $count;
    }

    public function clear(): void
    {
        $this->items = [];
        $this->components->clear();
        $this->discounts->clear();
    }

    public function getItemsTotal(): float
    {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item->getOriginalPrice() * $item->getQuantity();
            $discount = 0;
            // Apply matching discount if any
            foreach ($this->discounts->getList($item) as $discountOption) {
                $discount += $discountOption->getValue($item);
            }
            $total -= $discount;
        }
        return round($total, 2);
    }

    public function getTotal(): float
    {
        $total = $this->getItemsTotal();
        foreach ($this->components->getList() as $component) {
            $discount = 0;
            // Apply matching discount if any
            foreach ($this->discounts->getList($component) as $discountOption) {
                $discount += $discountOption->getValue($component);
            }
            $total += $component->getValue() - $discount;
        }
        return $total;
    }

    public function components(ComponentCollectionInterface $components = null): ComponentCollectionInterface
    {
        if ($components !== null) {
            $this->components = $components;
        }
        return $this->components;
    }

    public function discount(DiscountCollectionInterface $discount = null): DiscountCollectionInterface
    {
        if ($discount !== null) {
            $this->discounts = $discount;
        }
        return $this->discounts;
    }
}