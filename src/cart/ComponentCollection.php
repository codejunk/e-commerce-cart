<?php
namespace Cart;

class ComponentCollection implements ComponentCollectionInterface
{
    protected $items = [];

    /**
     * @param string $id
     * @return ComponentInterface|null
     */
    public function get(string $id): ?ComponentInterface
    {
        if (isset($this->items[$id])) {
            return $this->items[$id];
        }
        return null;
    }

    /**
     * @param ComponentInterface $component
     */
    public function add(ComponentInterface $component)
    {
        $this->items[$component->getId()] = $component;
    }

    /**
     * @return ComponentInterface[]
     */
    public function getList(): array
    {
        return $this->items;
    }

    /**
     * Clears components collection
     */
    public function clear(): void
    {
        $this->items = [];
    }
}