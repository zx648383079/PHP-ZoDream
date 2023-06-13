<?php
declare(strict_types=1);
namespace Module\Template\Domain\Pages;

use Module\Template\Domain\Weights\INode;
use Module\Template\Domain\Weights\Node;
use Zodream\Disk\Directory;
use Zodream\Disk\File;
use Zodream\Helpers\Str;
use Exception;
use Zodream\Template\ITheme;

class Page implements IPage, ITheme {

    protected array $nodeItems = [];
    /**
     * @var INode[]
     */
    protected array $nodeInstances = [];

    protected array $handleItems = [];
    /**
     *  数据缓存
     * @var array
     */
    protected array $handleData = [];

    public function __construct() {
        $this->loadNodes();
    }

    public function getRoot(): Directory
    {
        return app_path();
    }

    public function getFile(string $name): File
    {
        return $this->getRoot()->file($name);
    }

    protected function loadNodes() {
        $data = config('view.weights', []);
        foreach ($data as $key => $item) {
            $this->register($key, $item);
        }
    }

    public function cache() {
        static $driver = null;
        if (empty($driver)) {
            $driver = cache()->store('nodes');
        }
        return $driver;
    }

    public function register(string|int $name, string $node) {
        $this->nodeItems[$this->formatName($name)] = $node;
        return $this;
    }

    public function instance(string|int $name): INode {
        $name = $this->formatName($name);
        if (!isset($this->nodeItems[$name])) {
            return $this->notFoundNode($name);
        }
        $node = $this->nodeItems[$name];
        if (!isset($this->nodeInstances[$node])) {
            $this->nodeInstances[$node] = new $node($this);
        }
        return clone $this->nodeInstances[$node];
    }

    public function trigger(string $name) {
        if (isset($this->handleData[$name]) || array_key_exists($name, $this->handleData)) {
            return $this->handleData[$name];
        }
        if (isset($this->handleItems[$name])) {
            return $this->handleData[$name] = call_user_func($this->handleItems[$name], $this);
        }
        return $this;
    }

    public function on(string $name, callable $func) {
        $this->handleItems[$name] = $func;
        return $this;
    }

    /**
     * @param null $type
     * @return mixed
     */
    public function render($type = null) {
        return '';
    }

    public function node(string|int $name, array $attributes = []): INode {
        $node = $this->instance($name);
        $node->attr($attributes);
        return $node;
    }

    public function eachNode(callable $cb, $isGoode = false) {
        foreach ($this->nodeItems as $name => $_) {
            $node = $this->node($name);
            if ($isGoode === $node->isGlobe()) {
                call_user_func($cb, $node, $name);
            }
        }
        return $this;
    }

    protected function formatName(string|int $name): string|int {
        return is_numeric($name) ? $name : Str::studly($name);
    }

    protected function notFoundNode(string $name): INode {
        if (app()->isDebug()) {
            throw new Exception(
                sprintf('"%s" node not register', $name)
            );
        }
        return new Node($this);
    }

    public function __call($name, $arguments) {
        return $this->node($name, ...$arguments);
    }
}