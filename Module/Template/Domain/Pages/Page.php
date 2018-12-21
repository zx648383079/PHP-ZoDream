<?php
namespace Module\Template\Domain\Pages;

class Page implements IPage {

    protected $node_list = [];

    public function __construct() {
        $this->loadNodes();
    }

    protected function loadNodes() {
        $data = config()->getConfigByFile('weight');
        foreach ($data as $key => $item) {
            $this->register($key, $item);
        }
    }

    public function register(string $name, string $node) {
        $this->node_list[$name] = $node;
        return $this;
    }

    public function trigger(string $name) {
        // TODO: Implement trigger() method.
    }

    public function on(string $name, callable $func) {
        // TODO: Implement on() method.
    }

    public function render($type = null) {
        // TODO: Implement render() method.
    }

    public function node(string $name, array $attributes = []) {
        // TODO: Implement node() method.
    }

    public function __call($name, $arguments) {
        return $this->node($name, ...$arguments);
    }
}