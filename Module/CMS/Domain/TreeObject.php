<?php
declare(strict_types=1);
namespace Module\CMS\Domain;


use Traversable;
use Zodream\Helpers\Tree as TreeHelper;
use Zodream\Html\Tree as TreeConverter;

class TreeObject implements \IteratorAggregate {

    const CHILDREN_COUNT_KEY = 'children_count';
    const PARENT_PATH_KEY = 'parent_path';
    const CHILDREN_KEY = 'children_ids';

    protected array $pathItems = [];
    protected array $data = [];
    protected ?TreeConverter $converter = null;

    public function __construct(
        array $data = []
    ) {
        $this->format($data);
    }

    public function getChildrenId(int $parent, bool $withSelf = true): array {
        $items = [];
        foreach ($this->pathItems as $path) {
            $i = array_search($parent, $path, true);
            if ($i === false) {
                continue;
            }
            for (;$i < count($path) - 1; ++ $i) {
                $id = $path[$i];
                if (in_array($id, $items)) {
                    continue;
                }
                $items[] = $id;
            }
        }
        if (!$withSelf) {
            return $items;
        }
        $items[] = $parent;
        return $items;
    }

    public function getParentId(int $id): array {
        foreach ($this->pathItems as $path) {
            $i = array_search($id, $path, true);
            if ($i !== false) {
                return array_slice($path, 0, $i);
            }
        }
        return [];
    }

    public function getRootId(int $id): int {
        foreach ($this->pathItems as $path) {
            if (in_array($id, $path)) {
                return $path[0];
            }
        }
        return $id;
    }

    /**
     * 判断两个数据之间的关系
     * @param int|string $itemId
     * @param int|string $targetId
     * @return int 0 无关系， 1 是target的父级 2 是自身 3 是target 的子级
     */
    public function hasLink(int|string $itemId, int|string $targetId): int {
        $itemId = intval($itemId);
        $targetId = intval($targetId);
        if ($itemId === $targetId) {
            return 2;
        }
        if (!isset($this->data[$itemId]) || !isset($this->data[$targetId])) {
            return 2;
        }
        $path = $this->getParentId($itemId);
        if (in_array($targetId, $path)) {
            return 3;
        }
        $next = $this->getParentId($targetId);
        return in_array($itemId, $next) ? 1 : 0;
    }

    public function converter(): TreeConverter {
        if (empty($this->converter)) {
            $this->converter = new TreeConverter($this->data);
        }
        return $this->converter;
    }

    public function toIdTree(): array {
        return $this->converter()->makeIdTree();
    }

    public function __wakeup(): void {
    }

    public function __sleep(): array {
        return ['data', 'pathItems'];
    }

    public function all(): array {
        return array_values($this->data);
    }

    public function getIterator(): Traversable {
        return new \ArrayIterator($this->all());
    }

    /**
     * 转成树形结构的数组
     * @param array $data
     */
    protected function format(array $data): void {
        if (empty($data)) {
            return;
        }
        $childMap = [];
        foreach ($data as $item) {
            $parentId = intval($item['parent_id']);
            $id = intval($item['id']);
            if (!isset($childMap[$parentId])) {
                $childMap[$parentId] = [];
            }
            $childMap[$parentId][] = $id;
        }
        $paths = []; // 纯 id 组成的链路
        $groupTemp = [];
        if (isset($childMap[0])) {
            foreach ($childMap[0] as $id) {
                $groupTemp[$id] = [$id];
            }
        }
        while (!empty($groupTemp)) {
            $temp = [];
            foreach ($groupTemp as $end => $path) {
                if (!isset($childMap[$end])) {
                    $paths[] = $path;
                    continue;
                }
                foreach ($childMap[$end] as $id) {
                    if (in_array($id, $path)) {
                        // 无效，死循环id
                        continue;
                    }
                    $temp[$id] = array_merge($path, [$id]);
                }
            }
            $groupTemp = $temp;
            unset($temp);
        }
        unset($groupTemp);
        $items = [];
        foreach ($data as $item) {
            $formatted = $item->toArray();
            $formatted[static::CHILDREN_COUNT_KEY] = isset($childMap[$item['id']]) ? count($childMap[$item['id']]) : 0;
            $items[$item['id']] = $formatted;
        }
        unset($data, $childMap);
        $this->data = $items;
        $this->pathItems = $paths;
    }
}