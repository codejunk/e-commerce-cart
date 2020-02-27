<?php
use codejunk\ecommerce\cart\Cart;
use codejunk\ecommerce\cart\Item;
use codejunk\ecommerce\cart\DiscountItem;
use codejunk\ecommerce\cart\DiscountShipping;
use codejunk\ecommerce\cart\DiscountTax;
use codejunk\ecommerce\cart\Shipping;
use codejunk\ecommerce\cart\Tax;

/**
 * Class CartWrapper
 */
class CartWrapper
{
    private function __construct()
    {
    }

    public static function init(): \codejunk\ecommerce\cart\CartInterface
    {
        $repository = new \codejunk\ecommerce\cart\CartRepositorySession();
        if (!$cart = $repository->load()) {
            $cart = new Cart($repository);
        }
        return $cart;
    }
}

$cart = CartWrapper::init();

if (!empty($_POST)) {
    $actionName = array_key_first($_POST['action']);
    $id = $_POST['action'][$actionName] ?? '';

    switch ($actionName) {
        case 'remove':
            $cart->remove($id);
            break;

        case 'increase':
            $cart->getItem($id)->setQuantity($cart->getItem($id)->getQuantity() + 1);
            break;

        case 'decrease':
            $cart->getItem($id)->setQuantity($cart->getItem($id)->getQuantity() - 1);
            break;
    }

    header('Location: demo.php');
    exit();
}

// Re-fill cart if cart is empty
if ($cart->getItemsCount() === 0) {
    $cart->clear();
    $cartItems = [
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
            5,
            ['weight' => 1.5, 'image' => 'product_3_image_URL']
        )
    ];
    foreach ($cartItems as $item) {
        $cart->add($item);
    }

    // Add shipping
    $shippingOrig = new Shipping(
        'Shipping',
        'USPS Ground Service',
        7.99,
        ['optionId' => 'usps-ground']
    );
    $cart->components()->add($shippingOrig);

    // Add tax
    $taxOrig = new Tax(
        'Tax',
        'CA Tax',
        9.25,
        ['cart' => $cart]
    );
    $cart->components()->add($taxOrig);

    // Apply a promo-code
    $cart->discount()->setCode('SOME-CODE');
    $cart->discount()->add(new DiscountItem('10-discount', '10% off all products', 10));
    $cart->discount()->add(new DiscountShipping('free-shipping', 'Free shipping', 100));
    $cart->discount()->add(new DiscountTax('tax-free', 'Tax free', 100));
}