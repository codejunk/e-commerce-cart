<?php
namespace codejunk\ecommerce\cart;

class ComponentCollection implements ComponentCollectionInterface
{
    protected $items = [];

    /**
     * @var \SplSubject
     */
    protected $subject;

    /**
     * ComponentCollection constructor.
     * @param \SplObserver $observer
     */
    public function __construct(\SplObserver $observer)
    {
        $this->subject = new EventSubject();
        $this->subject->attach($observer);
    }

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
        // Trigger cart change event
        $this->subject->notify(EventCartChange::class, $this);
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
        // Trigger cart change event
        $this->subject->notify(EventCartChange::class, $this);
    }
}
