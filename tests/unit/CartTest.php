<?php

use codejunk\ecommerce\cart\Cart;
use codejunk\ecommerce\cart\Item;
use codejunk\ecommerce\cart\Shipping;
use codejunk\ecommerce\cart\Tax;
use codejunk\ecommerce\cart\DiscountItem;
use codejunk\ecommerce\cart\DiscountShipping;
use codejunk\ecommerce\cart\DiscountTax;

class CartTest extends \Codeception\Test\Unit
{
    protected function getCartItems()
    {
        return [
            new Item(
                '1',
                'Product 1 title',
                99.99,
                1,
                ['weight' => 1.34, 'image' => 'product_1_image_URL']
            ),
            new Item(
                '2',
                'Product 2 title',
                69.99,
                1,
                ['weight' => 1.55, 'image' => 'product_2_image_URL']
            ),
            new Item(
                '3',
                'Product 3 title',
                79.77,
                3,
                ['weight' => 1.5, 'image' => 'product_3_image_URL']
            )
        ];
    }

    protected function getCart(): Cart
    {
        $cart = new Cart();

        foreach ($this->getCartItems() as $item) {
            $cart->add($item);
        }

        return $cart;
    }

    public function testAdd()
    {
        $cart = new Cart();
        $origItems = [];
        foreach ($this->getCartItems() as $item) {
            $origItems[$item->getId()] = $item;
            $cart->add($item);
        }


        $this->assertNotEmpty($items = $cart->getItems());
        $this->assertEquals(count($items), 3);

        foreach ($this->getCartItems() as $item) {
            $origItem = $origItems[$item->getId()];
            $this->assertEquals($item->getId(), $origItem->getId());

            // Test item options
            switch ($item->getId()) {
                case '1':
                    $this->assertEquals($item->getOption('weight'), 1.34);
                    $this->assertEquals($item->getOption('image'), 'product_1_image_URL');
                    break;
                case '2':
                    $this->assertEquals($item->getOption('weight'), 1.55);
                    $this->assertEquals($item->getOption('image'), 'product_2_image_URL');
                    break;
                case '3':
                    $this->assertEquals($item->getOption('weight'), 1.5);
                    $this->assertEquals($item->getOption('image'), 'product_3_image_URL');
                    break;
            }
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


    public function testComponents()
    {
        $cart = $this->getCart();

        // Test shipping
        $shippingOrig = new Shipping(
            'shipping',
            'USPS Ground Service',
            7.99,
            ['optionId' => 'usps-ground']
        );
        $cart->components()->add($shippingOrig);

        $shipping = $cart->components()->get('shipping');
        $this->assertEquals($shipping->getOption('optionId'), 'usps-ground');
        $this->assertEquals($shipping->getValue(), 7.99);
        $this->assertEquals($cart->getTotal(), 409.29 + 7.99);

        // Test tax
        $taxOrig = new Tax(
            'tax',
            'CA Tax',
            9.25,
            ['cart' => $cart]
        );
        $cart->components()->add($taxOrig);

        $tax = $cart->components()->get('tax');
        $this->assertEquals($tax->getValue(), 37.86);
        $this->assertEquals($cart->getTotal(), 409.29 + 7.99 + 37.86);

        // Test tax after item quantity change
        $cart->getItem('1')->setQuantity(2);
        $this->assertEquals($tax->getValue(), 47.11);
        $this->assertEquals($cart->getTotal(), (409.29 + 99.99) + 7.99 + 47.11);
    }

    public function testDiscount()
    {
        $cart = $this->getCart();

        $shippingOrig = new Shipping(
            'shipping',
            'USPS Ground Service',
            7.99,
            ['optionId' => 'usps-ground']
        );
        $cart->components()->add($shippingOrig);

        $taxOrig = new Tax(
            'tax',
            'CA Tax',
            9.25,
            ['cart' => $cart]
        );
        $cart->components()->add($taxOrig);
        $this->assertEquals($cart->getTotal(), 409.29 + 7.99 + 37.86);

        $cart->discount()->setCode('SOME-CODE');


        $cart->discount()->add(new DiscountItem('10-discount', '10% off all products', 10));
        $cart->discount()->add(new DiscountShipping('free-shipping', 'Free shipping', 100));
        $cart->discount()->add(new DiscountTax('tax-free', 'Tax free', 100));

        $this->assertEquals($cart->discount()->get('free-shipping')->getId(), 'free-shipping');
        $this->assertEquals($cart->discount()->get('tax-free')->getId(), 'tax-free');
        $this->assertEquals($cart->discount()->getCode(), 'SOME-CODE');


        // Test items discounts
        $this->assertEquals($cart->getItem('1')->getPrice(), 89.99);
        $this->assertEquals($cart->getItem('3')->getPrice(), 71.79);

        $this->assertEquals($cart->getTotal(), 368.36);


        $cart->discount()->clear();
        $this->assertEquals($cart->discount()->getCode(), null);
        $this->assertEquals($cart->getTotal(), 409.29 + 7.99 + 37.86);

        //$this->assertEquals($cart->getDiscount()->getTitle(), '10% off all products');
    }


    public function testRepository()
    {
        $cart = $this->getCart();
        $repository = new \codejunk\ecommerce\cart\CartRepositorySession();
        $this->assertTrue($repository->save($cart));

        $cart = $repository->load();
        $this->assertTrue($cart->getTotal() > 0);
    }


    public function testOptions()
    {
        $cart = $this->getCart();
        $cart->setOption('orderId', 123456);
        $cart->setOption('paymentMethod', 'PayPal');

        $this->assertEquals($cart->getOption('orderId'), 123456);
        $this->assertEquals($cart->getOption('paymentMethod'), 'PayPal');
    }
}
