<?php
namespace Cart;



class Cart implements CartInterface
{
    /**
     * @var ItemInterface[]
     */
    protected $items = [];

    /**
     * @var CartObserver
     */
    protected $observer;


    /**
     * @var ComponentCollection
     */
    protected $components;

    /**
     * Cart constructor.
     */
    public function __construct()
    {
        $this->observer = new CartObserver($this);
        $this->components = new ComponentCollection();
    }


    public function add(ItemInterface $item): int
    {
        if (isset($this->items[$item->getId()])) {
            $this->items[$item->getId()]->setQuantity(
                $this->items[$item->getId()]->getQuantity() + 1
            );
        } else {
            $itemDecorator = new ItemDecorator($item, $this->observer);
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
    }

    public function getItemsTotal(): float
    {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item->getPrice() * $item->getQuantity();
        }
        return round($total, 2);
    }

    public function getTotal(): float
    {
        $total = $this->getItemsTotal();
        foreach ($this->components->getList() as $component) {
            $total += $component->getValue();
        }
        return $total;
    }

    public function getComponents(): ComponentCollectionInterface
    {
        return $this->components;
    }
}