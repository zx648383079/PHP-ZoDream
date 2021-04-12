<?php
namespace Module\Template\Domain\Pages;


use Zodream\Infrastructure\Caching\Cache;

interface IPage {

    /**
     * @return Cache
     */
    public function cache();

    public function register(string|int $name, string $node);

    public function trigger(string $name);

    public function on(string $name, callable $func);

    /**
     * @param null $type
     * @return mixed
     */
    public function render($type = null);

    public function node(string|int $name, array $attributes = []);

}