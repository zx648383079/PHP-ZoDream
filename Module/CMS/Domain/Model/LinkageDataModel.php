<?php
namespace Module\CMS\Domain\Model;

use Domain\Model\Model;

/**
 * Class LinkageModel
 * @package Module\CMS\Domain\Model
 * @property integer $id
 * @property integer $linkage_id
 * @property string $name
 * @property integer $parent_id
 * @property integer $position
 * @property string $full_name
 */
class LinkageDataModel extends Model {
    public static function tableName() {
        return 'cms_linkage_data';
    }

    protected function rules() {
        return [
            'linkage_id' => 'required|int',
            'name' => 'required|string:0,100',
            'parent_id' => 'int',
            'position' => 'int:0,999',
            'full_name' => 'required|string:0,100',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'linkage_id' => 'Link Id',
            'name' => '名称',
            'parent_id' => 'Parent Id',
            'position' => '排序',
            'full_name' => '完整名称',
        ];
    }

    public function createFullName() {
        if ($this->parent_id < 1) {
            $this->full_name = $this->name;
            return $this;
        }
        $this->full_name = sprintf('%s %s', static::where('id', $this->parent_id)->value('full_name'), $this->name);
        return $this;
    }
}