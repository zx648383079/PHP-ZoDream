<?php
namespace Module\Template\Domain\Weights;


interface INode {

    /**
     * @return bool 是否支持嵌套
     */
    public function isNest(): bool;

    public function attr($key, $value = null);

    public function render($type = null);
}