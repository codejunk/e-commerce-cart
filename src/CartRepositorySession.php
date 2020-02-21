<?php
namespace codejunk\ecommerce\cart;


class CartRepositorySession implements CartRepositoryInterface
{
    const STORAGE_DATA_KEY = 'cart_storage';

    /**
     * CartRepositorySession constructor.
     */
    public function __construct()
    {
        $id = session_id();
        if ($id == '') {
            session_start();
        }
    }

    public function load(): CartInterface
    {
        if (isset($_SESSION[self::STORAGE_DATA_KEY])) {
            return $_SESSION[self::STORAGE_DATA_KEY];
        }
    }

    public function save(CartInterface $cart): bool
    {
        $_SESSION[self::STORAGE_DATA_KEY] = $cart;

        return true;
    }

}