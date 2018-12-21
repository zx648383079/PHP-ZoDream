<?php
namespace Module\Template\Domain\Pages;


interface IPage {

    public function register($name, $node);

    public function trigger($name);

    public function on($name, $func);

    public function render($type = null);

}