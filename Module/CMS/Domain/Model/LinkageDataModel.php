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
 * @property string $description
 * @property string $thumb
 */
class LinkageDataModel extends Model {
    public static function tableName(): string {
        return 'cms_linkage_data';
    }

    protected function rules(): array {
        return [
            'linkage_id' => 'required|int',
            'name' => 'required|string:0,100',
            'parent_id' => 'int',
            'position' => 'int:0,999',
            'full_name' => 'required|string:0,100',
            'description' => 'string:0,255',
            'thumb' => 'string:0,255',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'linkage_id' => 'Link Id',
            'name' => '名称',
            'parent_id' => 'Parent Id',
            'position' => '排序',
            'full_name' => '完整名称',
            'description' => '备注',
            'thumb' => '图片',
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