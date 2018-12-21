<?php
namespace Module\Template\Domain\Pages;


interface IPage {

    public function register(string $name, string $node);

    public function trigger(string $name);

    public function on(string $name, callable $func);

    public function render($type = null);

    public function node(string $name, array $attributes = []);

}