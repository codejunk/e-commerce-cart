<?php
namespace Cart;


use SplSubject;

class CartObserver implements \SplObserver
{
    /**
     * @var Cart
     */
    protected $cart;

    /**
     * CartObserver constructor.
     * @param Cart $cart
     */
    public function __construct(Cart $cart)
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