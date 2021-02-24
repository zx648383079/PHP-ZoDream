<?php
declare(strict_types=1);
namespace Infrastructure;

use Zodream\Database\Model\Query;

/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/4/3
 * Time: 15:02
 */


class Tree {

    public function __construct(
        protected Query $query,
        protected string $idKey = 'id',
        protected string $leftKey = 'left',
        protected string $rightKey = 'right',
        protected string $parentKey = 'parent_id',
        protected string $sortKey = 'position',
        protected string $sortOrder = 'asc',
        protected string $levelKey = 'level',
    ) {
    }

    public function getNode($id, $withChildren = false, $withPath = false, $allChildren = false) {
        $node = $this->query()->where($this->idKey, $id)->first();
        if (empty($node)) {
            return null;
        }
        if ($withChildren) {
            $node['children'] = $this->getChildren($id);
        }
        if ($withPath) {
            $node['path'] = $this->getPath($node[$this->leftKey], $node[$this->rightKey]);
        }
        if ($allChildren) {
            $node['children'] = $this->getAllChildren($node[$this->leftKey], $node[$this->rightKey]);
        }
        return $node;
    }

    public function getChildren($id) {
        return $this->query()->where($this->parentKey, $id)
            ->orderBy($this->sortKey, $this->sortOrder)->get();
    }

    public function getAllChildren($left, $right) {
        return $this->query()
            ->where($this->leftKey, '>', $left)
            ->where($this->rightKey, '<', $right)
            ->orderBy($this->leftKey, 'asc')->get();
    }

    public function getPath($left, $right) {
        return $this->query()
            ->where($this->leftKey, '<', $left)
            ->where($this->rightKey, '>', $right)
            ->orderBy($this->leftKey, 'asc')->get();
    }

    public function create($parent, $position = 0, $data = []) {
        $position = intval($position);
        $parent = $this->getNode($parent, true);
        if ($position >= count($parent['children'])) {
            $position = count($parent['children']);
        }
        if($position == 0 || !isset($parent['children'][$position])) {
            $left = $parent[$this->leftKey];
            $right = $parent[$this->rightKey];
        }
        else {
            $left = $parent['children'][$position][$this->leftKey];
            $right = $parent['children'][$position][$this->leftKey] + 1;
        }
        $this->query()
            ->where($this->parentKey, $parent[$this->idKey])
            ->where($this->sortKey, $position)->updateIncrement($this->sortKey);
        $this->query()->where($this->leftKey, '>=', $left)
            ->updateIncrement($this->leftKey, 2);
        $this->query()->where($this->rightKey, '>=', $right)
            ->updateIncrement($this->rightKey, 2);
        return $this->query()
            ->insert(array_merge($data, [
                $this->leftKey => $left,
                $this->rightKey => $right,
                $this->parentKey => $parent[$this->idKey],
                $this->levelKey => $parent[$this->levelKey] + 1,
                $this->sortKey => $position,
            ]));
    }

    public function move($id, $parent, $position = 0) {
        $position = intval($position);
        $parent	= $this->getNode($parent, true, true);
        $node = $this->getNode($id, false, true, true);
        if (empty($parent['children'])) {
            $position = 0;
        }
        if ($node[$this->parentKey] == $parent[$this->idKey] && $position > $node[$this->sortKey]) {
            $position ++;
        }
        if($position >= count($parent['children'])) {
            $position = count($parent['children']);
        }
        if($node[$this->leftKey] < $parent[$this->leftKey] && $node[$this->rightKey] > $parent[$this->rightKey]) {
            return false;
        }

        $ids = array($node[$this->idKey]);
        if ($node['children'] && is_array($node['children'])) {
            foreach($node['children'] as $c) {
                $ids[] = $c[$this->idKey];
            }
        }
        $width = (int)$node[$this->rightKey] - (int)$node[$this->leftKey] + 1;

        $this->query()
            ->where('id', '!=', $node[$this->idKey])
            ->where($this->parentKey, $parent[$this->idKey])
            ->where($this->sortKey, '>=', $position)->updateIncrement($this->sortKey);

        if(empty($parent['children']) || !isset($parent['children'][$position])) {
            $right = $left = $parent[$this->rightKey];
        } else {
            $left = $parent['children'][$position][$this->leftKey];
            $right = $left + 1;
        }
        $this->query()->where($this->leftKey, '>=', $left)
            ->whereNotIn($this->idKey, $ids)->updateIncrement($this->leftKey, $width);
        $this->query()->where($this->rightKey, '>=', $right)
            ->whereNotIn($this->idKey, $ids)->updateIncrement($this->rightKey, $width);

        $diff = $left - $node[$this->leftKey];
        if($diff > 0) {
            $diff = $diff - $width;
        }
        $ldiff = ((int)$parent[$this->levelKey] + 1) - (int)$node[$this->levelKey];
        $this->query()->whereIn($this->idKey, $ids)->updateIncrement(array(
            $this->rightKey => $diff,
            $this->leftKey => $diff,
            $this->levelKey => $ldiff
        ));

        $this->query()->where($this->idKey, $node[$this->idKey])->update(array(
            $this->sortKey => $position,
            $this->parentKey => $parent[$this->idKey]
        ));

        $this->query()->where($this->parentKey, $node[$this->parentKey])
            ->where($this->sortKey, '>', $node[$this->sortKey])
            ->updateDecrement($this->sortKey);

        $this->query()->where($this->leftKey, '>', $node[$this->rightKey])
            ->whereNotIn($this->idKey, $ids)
            ->updateDecrement($this->leftKey, $width);

        $this->query()
            ->where($this->rightKey, '>', $node[$this->rightKey])
            ->whereNotIn($this->idKey, $ids)->updateDecrement($this->rightKey,$width);
        return true;
    }

    public function delete($id) {
        $data = $this->getNode($id, true, true);
        $dif = $data[$this->rightKey] - $data[$this->leftKey] + 1;

        $this->query()->where($this->leftKey, '>=', $data[$this->leftKey])
            ->where($this->rightKey, '<=', $data[$this->rightKey])->delete();

        $this->query()->where($this->leftKey, '>', $data[$this->rightKey])
            ->updateDecrement($this->leftKey, $dif);

        $this->query()->where($this->rightKey, '>', $data[$this->leftKey])
            ->updateDecrement($this->rightKey, $dif);
        $this->query()
            ->where($this->parentKey, '>', $data[$this->parentKey])
            ->where($this->sortKey, '>', $data[$this->sortKey])
            ->updateDecrement($this->sortKey);
        return true;
    }

    public function update($id, $data) {
        return $this->query()->where($this->idKey, $id)->update(array(
            'name' => $data['name'],
            'url' => $data['url']
        ));
    }


    protected function query() {
        return clone $this->query;
    }

}