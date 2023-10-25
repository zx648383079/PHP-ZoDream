<?php
declare(strict_types=1);
namespace Module\Template\Domain\Weights;

use Module\Template\Domain\Pages\IPage;
use Zodream\Infrastructure\Caching\Cache;

class Node implements INode {
    /**
     * @var IPage
     */
    protected $page;
    protected array $attributes = [];

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

    protected function registerAsync(): void {

    }

    public function isNest(): bool {
        return false;
    }

    public function isGlobe(): bool {
        return false;
    }

    public function attr(mixed $key, mixed $value = null): mixed {
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

    public function render(string $type = ''): mixed {
        return '';
    }

    public function __toString() {
        return $this->render();
    }
}