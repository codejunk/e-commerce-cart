<?php
namespace codejunk\ecommerce\cart;


class EventSubject implements \SplSubject
{
    /**
     * @var \SplObjectStorage
     */
    private $observers;

    public function __construct()
    {
        $this->observers = new \SplObjectStorage;
    }

    public function attach(\SplObserver $observer): void
    {
        $this->observers->attach($observer);
    }

    public function detach(\SplObserver $observer): void
    {
        $this->observers->detach($observer);
    }

    public function notify(string $event = '', $data = null): void
    {
        foreach ($this->observers as $observer) {
            $observer->update($this, $event, $data);
        }
    }
}