<?php

use Cart\Cart;
use Cart\Item;

class CartTest extends \Codeception\Test\Unit
{

    protected function getCartItems()
    {
        return [
            new Item('1', 'Product 1 title', 99.99, 1.34),
            new Item('2', 'Product 2 title', 69.99, 1.55),
            new Item('3', 'Product 3 title', 79.77, 1.44, 3)
        ];
    }

    protected function getCart(): Cart
    {
        $cart = new Cart();

        foreach ($this->getCartItems() as $item)
            $cart->add($item);

        return $cart;
    }

    public function testAdd()
    {
        $cart = $this->getCart();

        $this->assertNotEmpty($items = $cart->getItems());
        $this->assertEquals(count($items), 3);

        foreach ($this->getCartItems() as $item) {
            $this->assertEquals($item->getId(), $items[$item->getId()]->getId());
        }
    }

    public function testSetItemQuantity()
    {
        $cart = $this->getCart();
        $cart->getItem('1')->setQuantity(0);
        $this->expectException(InvalidArgumentException::class);
        $cart->getItem('1');
    }


    public function testGetItem()
    {
        $cart = $this->getCart();
        $this->assertEquals($cart->getItemsCount(), 5);
        $this->assertNotEmpty($item = $cart->getItem('1'));
        $item->setQuantity(3);
        $this->assertEquals($cart->getItemsCount(), 7);
    }


    public function testRemove()
    {
        $cart = $this->getCart();
        $cart->remove('1');
        $this->assertEquals($cart->getItemsCount(), 4);

        $cart->remove('3');
        $this->assertEquals($cart->getItemsCount(), 1);

        $this->expectException(InvalidArgumentException::class);
        $cart->getItem('3');
    }

    public function testGetTotals()
    {
        $cart = $this->getCart();
        $this->assertEquals($cart->getTotal(), 409.29);
        $this->assertEquals($cart->getItemsTotal(), 409.29);

        $cart->getItem('1')->setQuantity(2);

        $this->assertEquals($cart->getTotal(), 409.29 + 99.99);
        $this->assertEquals($cart->getItemsTotal(), 409.29 + 99.99);
    }


    public function testClear()
    {
        $cart = $this->getCart();
        $cart->clear();
        $this->assertEmpty($cart->getItems());
        $this->assertEquals($cart->getItemsCount(), 0);
        $this->assertEquals($cart->getItemsTotal(), 0);
        $this->assertEquals($cart->getTotal(), 0);
    }

}
