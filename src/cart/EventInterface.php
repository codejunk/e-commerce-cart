<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 1/30/20
 * Time: 6:25 PM
 */

namespace Cart;


interface EventInterface
{
    public function handle(): void;
}