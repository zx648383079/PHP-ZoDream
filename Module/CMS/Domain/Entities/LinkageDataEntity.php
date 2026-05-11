<?php
namespace Module\CMS\Domain\Entities;


use Domain\Entities\Entity;

/**
 * @property integer $id
 * @property integer $linkage_id
 * @property string $name
 * @property integer $parent_id
 * @property integer $position
 * @property string $full_name
 * @property string $description
 * @property integer $locale_group_id
 * @property string $thumb
 */
class LinkageDataEntity extends Entity {
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
            'locale_group_id' => 'int',
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
            'locale_group_id' => '本地化分组',
            'description' => '备注',
            'thumb' => '图片',
        ];
    }
}