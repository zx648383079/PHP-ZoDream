<?php
namespace Module\Template\Domain\Pages;

use Module\Template\Domain\Weights\INode;
use Zodream\Helpers\Str;
use Exception;

class Page implements IPage {

    protected $node_list = [];
    /**
     * @var INode[]
     */
    protected $node_instance = [];

    protected $handle_list = [];
    /**
     *  数据缓存
     * @var array
     */
    protected $handle_data = [];

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
        $this->node_list[Str::studly($name)] = $node;
        return $this;
    }

    public function instance($name): INode {
        $name = Str::studly($name);
        if (!isset($this->node_list[$name])) {
            throw new Exception(
                sprintf('"%s" node not register', $name)
            );
        }
        $node = $this->node_list[$name];
        if (!isset($this->node_instance[$node])) {
            $this->node_instance[$node] = new $node($this);
        }
        return clone $this->node_instance[$node];
    }

    public function trigger(string $name) {
        if (isset($this->handle_data[$name]) || array_key_exists($name, $this->handle_data)) {
            return $this->handle_data[$name];
        }
        if (isset($this->handle_list[$name])) {
            return $this->handle_data[$name] = call_user_func($this->handle_list[$name], $this);
        }
        return $this;
    }

    public function on(string $name, callable $func) {
        $this->handle_list[$name] = $func;
        return $this;
    }

    public function render($type = null) {
        // TODO: Implement render() method.
    }

    public function node(string $name, array $attributes = []): INode {
        $node = $this->instance($name);
        $node->attr($attributes);
        return $node;
    }

    public function __call($name, $arguments) {
        return $this->node($name, ...$arguments);
    }
}