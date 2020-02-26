<?php
namespace codejunk\ecommerce\cart;

/**
 * Class DiscountCollection
 * @package Cart
 */
class DiscountCollection implements DiscountCollectionInterface
{
    /**
     * @var string
     */
    protected $code;

    /**
     * @var DiscountInterface[]
     */
    protected $items = [];

    /**
     * @var \SplSubject
     */
    protected $subject;

    /**
     * DiscountCollection constructor.
     * @param $observer
     */
    public function __construct(\SplObserver $observer)
    {
        $this->subject = new EventSubject();
        $this->subject->attach($observer);
    }


    /**
     * @return null|string
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * @param string $id
     * @return DiscountInterface|null
     */
    public function get(string $id): ?DiscountInterface
    {
        if (isset($this->items[$id])) {
            return $this->items[$id];
        }
        return null;
    }

    /**
     * @param DiscountInterface $discount
     */
    public function add(DiscountInterface $discount)
    {
        $this->items[$discount->getId()] = $discount;
        // Trigger cart change event
        $this->subject->notify(EventCartChange::class, $this);
    }

    /**
     * @param null $object
     * @return DiscountInterface[]
     */
    public function getList($object = null): array
    {
        // Trying to find discounts matching to the object passed
        if ($object !== null) {
            $found = [];
            foreach ($this->items as $i) {
                if ($i->isValidFor($object)) {
                    $found[$i->getId()] = $i;
                }
            }
            return $found;
        }

        return $this->items;
    }

    /**
     * Clears discounts collection
     */
    public function clear(): void
    {
        $this->code = null;
        $this->items = [];
        // Trigger cart change event
        $this->subject->notify(EventCartChange::class, $this);
    }
}
