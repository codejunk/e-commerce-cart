<?php
namespace codejunk\ecommerce\cart;


use SplSubject;

class CartEventObserver implements \SplObserver
{
    /**
     * @var CartInterface
     */
    protected $cart;

    /**
     * CartEventObserver constructor.
     * @param CartInterface $cart
     */
    public function __construct(CartInterface $cart)
    {
        $this->cart = $cart;
    }

    public function update(SplSubject $subject, string $eventName = null, $data = null)
    {
        if (class_exists($eventName)) {
            /**
             * @var EventInterface $event;
             */
            $event = new $eventName;
            $event->handle($this->cart, $data);
        }
    }
}