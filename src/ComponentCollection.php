<?php
namespace codejunk\ecommerce\cart;

class ComponentCollection implements ComponentCollectionInterface
{
    /**
     * @var array
     */
    protected $items = [];

    /**
     * @var \SplSubject
     */
    protected $subject;


    /**
     * @var CartInterface
     */
    protected $cart;

    /**
     * ComponentCollection constructor.
     * @param \SplObserver $observer
     * @param CartInterface $cart
     */
    public function __construct(\SplObserver $observer, CartInterface $cart)
    {
        $this->subject = new EventSubject();
        $this->subject->attach($observer);

        $this->cart = $cart;
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
        $decorator = new ComponentDecorator($component, $this->cart);
        $this->items[$component->getId()] = $decorator;
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
