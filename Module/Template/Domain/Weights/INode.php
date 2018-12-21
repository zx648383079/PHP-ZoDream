<?php
namespace Module\Template\Domain\Weights;


interface INode {

    public function attr($key, $value = null);

    public function render($type = null);
}