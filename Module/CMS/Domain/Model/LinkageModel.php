<?php
namespace Module\CMS\Domain\Model;

use Domain\Model\Model;
use Zodream\Html\Tree;

/**
 * Class LinkageModel
 * @package Module\CMS\Domain\Model
 * @property integer $id
 * @property string $name
 * @property integer $type
 * @property string $code
 */
class LinkageModel extends Model {
    public static function tableName() {
        return 'cms_linkage';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,100',
            'type' => 'int:0,9',
            'code' => 'required|string:0,20',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => '名称',
            'type' => '类型',
            'code' => 'Code',
        ];
    }

    public static function idTree($id) {
        return cache()->getOrSet('cms_linkage_tree_'.$id, function () use ($id) {
            $tree = new Tree(LinkageDataModel::query()->where('linkage_id', $id)
                ->select('id', 'name', 'parent_id')->asArray()->all());
            return $tree->makeIdTree();
        }, 600);
    }
}