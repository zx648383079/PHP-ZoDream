<?php
declare(strict_types=1);
namespace Domain\Providers;

use Zodream\Infrastructure\Concerns\SingletonPattern;

class MemoryCacheProvider {

    use SingletonPattern;

    protected array $cacheData = [];

    public function has(string $func, string|int $key): bool {
        return isset($this->cacheData[$func]) && (isset($this->cacheData[$func][$key])
                || array_key_exists($key, $this->cacheData[$func]));
    }

    /**
     * 获取并进行数据缓存
     * @param string $func
     * @param string|int $key
     * @param callable|mixed $callback
     * @return mixed
     */
    public function getOrSet(string $func, string|int $key, mixed $callback): mixed {
        if (!isset($this->cacheData[$func])) {
            $this->cacheData[$func] = [];
        }
        if (isset($this->cacheData[$func][$key])
            || array_key_exists($key, $this->cacheData[$func])) {
            return $this->cacheData[$func][$key];
        }
        return $this->cacheData[$func][$key] = is_callable($callback) ?
            call_user_func($callback) : $callback;
    }

    /**
     * 设置缓存数据
     * @param string $func
     * @param string|int $key
     * @param mixed $data
     * @return void
     */
    public function set(string $func, string|int $key, mixed $data): void {
        if (!isset($this->cacheData[$func])) {
            $this->cacheData[$func] = [];
        }
        $this->cacheData[$func][$key] = $data;
    }

    /**
     * 如果不存则设置缓存数据
     * @param string $func
     * @param string|int $key
     * @param mixed $data
     * @return bool 缓存成功则true, 已存在则false
     */
    public function trySet(string $func, string|int $key, mixed $data): bool {
        if ($this->has($func, $key)) {
            return false;
        }
        $this->set($func, $key, $data);
        return true;
    }

    /**
     * 缓存多个数据
     * @param string $func
     * @param array $items
     * @param string $key
     * @return void
     */
    public function setAny(string $func, array $items, string $key = 'id') {
        foreach ($items as $item) {
            $this->set($func, $item[$key], $item);
        }
    }

    /**
     * 批量获取根据id，没缓存的进行缓存
     * @param array $idItems
     * @param string $func
     * @param callable $callback (key[]) => void
     * @param string $key
     * @return array
     */
    public function getAutoSet(array $idItems, string $func, callable $callback, string $key = 'id'): array {
        $notItems = [];
        $items = [];
        foreach ($idItems as $id) {
            if (isset($this->cacheData[$func][$id])) {
                $items[] = $this->cacheData[$func][$id];
                continue;
            }
            $notItems[] = $id;
        }
        if (empty($notItems)) {
            return $items;
        }
        $queries = call_user_func($callback, $notItems);
        static::setAny($func, $queries, $key);
        return array_merge($items, $queries);
    }
}