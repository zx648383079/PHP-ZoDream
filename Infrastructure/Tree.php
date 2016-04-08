<?php
namespace Infrastructure;
/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/4/3
 * Time: 15:02
 */
use Domain\Model\EmpireModel;

class Tree {

    /**
     * @var EmpireModel
     */
    protected $db;

    public function __construct() {
        $db = EmpireModel::query('tree');
    }

    public function getNode($id, $withChildren = false, $withPath = false, $allChildren = false) {
        $node = $this->db->findOne('id = '.intval($id));
        if (empty($node)) {
            return null;
        }
        if ($withChildren) {
            $node['children'] = $this->getChildren($id);
        }
        if ($withPath) {
            $node['path'] = $this->getPath($node['left'], $node['right']);
        }
        if ($allChildren) {
            $node['children'] = $this->getAllChildren($node['left'], $node['right']);
        }
        return $node;
    }

    public function getChildren($id) {
        return $this->db->find(array(
            'where' => 'parent_id = '.intval($id),
            'order' => 'position'
        ));
    }

    public function getAllChildren($left, $right) {
        return $this->db->find(array(
            'where' => array(
                '`left` > '.intval($left),
                '`right` < '.intval($right)
            ),
            'order' => '`left`'
        ));
    }

    public function getPath($left, $right) {
        return $this->db->find(array(
            'where' => array(
                '`left` < '.intval($left),
                '`right` > '.intval($right)
            ),
            'order' => '`left`'
        ));
    }

    public function create($parent, $position = 0, $data = array()) {
        $position = intval($position);
        $parent = $this->getNode($parent, true);
        if ($position >= count($parent['children'])) {
            $position = count($parent['children']);
        }
        if($position == 0 || !isset($parent['children'][$position])) {
            $left = $parent['right'];
            $right = $parent['right'];
        }
        else {
            $left = $parent['children'][$position]['left'];
            $right = $parent['children'][$position]['left'] + 1;
        }
        $this->db->updateOne('position', 'parent_id = '.$parent['id'].' AND position >= '.$position);
        $this->db->updateOne('left', '`left` >= '.$left, 2);
        $this->db->updateOne('right', '`right` >= '.$right, 2);
        return $this->db->insert('name, url, `left`, `right`, parent_id, level, position', '?,?,?,?,?,?,?', array(
            $data['name'],
            $data['url'],
            $left,
            $right,
            $parent['id'],
            $parent['level'] + 1,
            $position
        ));
    }

    public function move($id, $parent, $position = 0) {
        $position = intval($position);
        $parent	= $this->getNode($parent, true, true);
        $node = $this->getNode($id, false, true, true);
        if (empty($parent['children'])) {
            $position = 0;
        }
        if ($node['parent_id'] == $parent['id'] && $position > $node['position']) {
            $position ++;
        }
        if($position >= count($parent['children'])) {
            $position = count($parent['children']);
        }
        if($node['left'] < $parent['left'] && $node['right'] > $parent['right']) {
            return false;
        }

        $tmp = array($node['id']);
        if ($node['children'] && is_array($node['children'])) {
            foreach($node['children'] as $c) {
                $tmp[] = $c["id"];
            }
        }
        $ids = implode(',', $tmp);
        unset($tmp);
        $width = (int)$node['right'] - (int)$node['left'] + 1;

        $this->db->updateOne('position', array(
            'id != '.$node['id'],
            'parent_id = '.$parent['id'],
            'position >= '.$position
        ));

        if(empty($parent['children']) || !isset($parent['children'][$position])) {
            $right = $left = $parent['right'];
        } else {
            $left = $parent['children'][$position]['left'];
            $right = $left + 1;
        }
        $this->db->updateOne('left', array(
            '`left` >= '.$left,
            "id NOT IN ($ids)"
        ), $width);
        $this->db->updateOne('right', array(
            '`right` >= '.$right,
            "id NOT IN ($ids)"
        ), $width);

        $diff = $left - $node['left'];
        if($diff > 0) {
            $diff = $diff - $width;
        }
        $ldiff = ((int)$parent['level'] + 1) - (int)$node['level'];
        $this->db->updateOne(array(
            'right' => $diff,
            'left' => $diff,
            'level' => $ldiff
        ), "id IN ($ids)");

        $this->db->updateValues(array(
            'position' => $position,
            'parent_id' => $parent['id']
        ), 'id = '.$node['id']);

        $this->db->updateOne('position', array(
            'parent_id = '.$node['parent_id'],
            'position > '.$node['position']
        ), -1);

        $this->db->updateOne('left', array(
            '`left` > '.$node['right'],
            "id NOT IN ($ids)"
        ), -$width);

        $this->db->updateOne('right', array(
            '`right` > '.$node['right'],
            "id NOT IN ($ids)"
        ), -$width);
        return true;
    }

    public function delete($id) {
        $data = $this->getNode($id, true, true);
        $dif = $data['right'] - $data['left'] + 1;

        $this->db->deleteValues(array(
            '`left` >= '.$data['left'],
            '`right` <= '.$data['right']
        ));

        $this->db->updateOne('left', '`left` > '.$data['right'], -$dif);

        $this->db->updateOne('right', '`right` > '.$data['left'], -$dif);
        $this->db->updateOne('position', array(
            'parent_id > '.$data['parent_id'],
            'position > '.$data['position']
        ), -1);
        return true;
    }

    public function update($id, $data) {
        return $this->db->updateById($id, array(
            'name' => $data['name'],
            'url' => $data['url']
        ));
    }

}