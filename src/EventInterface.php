<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 1/30/20
 * Time: 6:25 PM
 */

namespace codejunk\ecommerce\cart;


interface EventInterface
{
    public function handle(): void;
}