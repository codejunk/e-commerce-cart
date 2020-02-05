<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 2/5/20
 * Time: 5:42 PM
 */

namespace Cart;


trait OptionTrait
{
    protected $options = [];

    public function getOption(string $name)
    {
        return $this->options[$name] ?? null;
    }

    public function setOption(string $name, $value): void
    {
        $this->options[$name] = $value;
    }
}