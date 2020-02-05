<?php
namespace Cart;


interface OptionInterface
{
    /**
     * @return mixed
     */
    public function getOption(string $name);

    /**
     * @param string $name
     * @param $value
     */
    public function setOption(string $name, $value): void;
}