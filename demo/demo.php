<?php
require_once '../vendor/autoload.php';
require_once 'actions.php';

/**
 * @var \codejunk\ecommerce\cart\CartInterface $cart
 */
?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="icon" href="data:;base64,iVBORw0KGgo=">
    <title>Shopping Cart Demo</title>
</head>
<body>
<div class="container">
    <h4>Shopping Cart</h4>
    <form method="post">
        <div class="row">
            <div class="col-lg-8 col-sm-12 pt-3">
                <div class="table-responsive">
                    <table class="table table-cart">
                        <thead>
                        <tr>
                            <th scope="col" class="w-50">Item</th>
                            <th scope="col" class="text-center">Qty</th>
                            <th scope="col" class="d-none d-sm-table-cell">Price</th>
                            <th scope="col" class="d-none d-sm-table-cell">Subtotal</th>
                            <th scope="col" class=""></th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php foreach ($cart->getItems() as $item): ?>
                            <tr>
                                <td>
                                    <div class="media">
                                        <!--<a href="#" class="mr-3 d-none d-md-block"><img src="" class="img-fluid" alt=""></a>-->
                                        <div class="media-body">
                                            <a href="#" class="h6"><?= $item->getTitle() ?></a>
                                            <div class="mb-1">
                                                <span class="d-inline d-sm-none small">Price:
                                                    <span class="text-theme">
                                                        <del>$<?= $item->getOriginalPrice() ?></del> $<?= $item->getPrice() ?>
                                                    </span>
                                                </span>
                                                <span class="badge badge-success">In Stock</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-outline-secondary" type="submit" name="action[decrease]" value="<?= $item->getId() ?>" id="button-addon1"> - </button>
                                        </div>
                                        <input type="text" class="form-control text-center" value="<?= $item->getQuantity() ?>" placeholder="" readonly="readonly" >
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="submit" name="action[increase]" value="<?= $item->getId() ?>" id="button-addon1"> + </button>
                                        </div>
                                    </div>
                                    <div class="d-block d-sm-none">
                                        <small>Subtotal </small>
                                        <div class="text-theme font-weight-bold">
                                            $<?= round($item->getPrice() * $item->getQuantity(), 2) ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="d-none d-sm-table-cell">
                                    <ul class="card-text list-inline">
                                        <li class="list-inline-item">
                                            <span class="text-theme">$<?= $item->getPrice() ?></span><br/>
                                            <span class="text-theme"><del>$<?= $item->getOriginalPrice() ?></del></span>
                                        </li>
                                    </ul>
                                </td>
                                <td class="d-none d-sm-table-cell">
                                    <strong>$<?= round($item->getPrice() * $item->getQuantity(), 2) ?></strong>
                                </td>
                                <td class="">
                                    <div class="btn-group btn-group-sm">
                                        <button data-product="101" class="btn btn-light btn-down btn-remove" type="submit" name="action[remove]" value="<?= $item->getId() ?>">
                                            <svg class="bi bi-trash-fill" width="1.5em" height="1.5em" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M4.5 3a1 1 0 00-1 1v1a1 1 0 001 1H5v9a2 2 0 002 2h6a2 2 0 002-2V6h.5a1 1 0 001-1V4a1 1 0 00-1-1H12a1 1 0 00-1-1H9a1 1 0 00-1 1H4.5zm3 4a.5.5 0 01.5.5v7a.5.5 0 01-1 0v-7a.5.5 0 01.5-.5zM10 7a.5.5 0 01.5.5v7a.5.5 0 01-1 0v-7A.5.5 0 0110 7zm3 .5a.5.5 0 00-1 0v7a.5.5 0 001 0v-7z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>


                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-lg-4 col-sm-12">
                <div class="row">
                    <div class="col-12"><h3><div class="text-right mt-2">Totals</div></h3></div>
                </div>

                <div class="row justify-content-between">
                    <div class="col-12">
                        <div class="mb-3">You have <?= $cart->getItemsCount() ?> item(s) in your cart</div>
                        <div class="d-flex">
                            <div>Bag Total</div>
                            <div class="ml-auto font-weight-bold">
                                $<?= $cart->getItemsTotal() ?>
                            </div>
                        </div>
                        <hr class="my-1">

                        <?php foreach ($cart->components()->getList() as $component): ?>
                            <div class="d-flex">
                                <div class="text-nowrap"><?= $component->getId() ?></div>
                                <div class="ml-auto font-weight-bold">
                                    $<?= $component->getValue() ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <hr class="my-1">

                        <div class="d-flex">
                            <div>Discount <br/><strong><?= $cart->discount()->getCode() ?></strong></div>
                            <div class="ml-auto font-weight-bold">
                                <?= $cart->getDiscountTotal() > 0 ? '-' : '' ?>$<?= $cart->getDiscountTotal() ?>
                            </div>
                        </div>
                        <hr class="my-1">

                        <div class="d-flex">
                            <div>Order Total</div>
                            <div class="font-weight-bold ml-auto text-theme h5 mb-0">$<?= $cart->getTotal() ?></div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </form>
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>

