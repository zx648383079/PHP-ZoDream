<?php
namespace Module\Template\Domain\Weights;

use Module\Template\Domain\Pages\IPage;
use Zodream\Infrastructure\Caching\Cache;

class Node implements INode {
    /**
     * @var IPage
     */
    protected $page;
    protected $attributes = [];

    public function __construct(IPage $page) {
        $this->page = $page;
        $this->registerAsync();
    }

    /**
     * 缓存
     * @return Cache
     */
    public function cache() {
        return $this->page->cache();
    }

    protected function registerAsync() {

    }

    public function isNest(): bool {
        return false;
    }

    public function isGlobe(): bool
    {
        return false;
    }

    public function attr($key, $value = null) {
        if (is_array($key)) {
            $this->attributes = array_merge($this->attributes, $key);
            return $this;
        }
        if (!is_null($value)) {
            $this->attributes[$key] = $value;
            return $this;
        }
        return $this->attributes[$key] ?? null;
    }

    public function render($type = null) {

        return '';
    }

    public function __toString() {
        return $this->render();
    }
}