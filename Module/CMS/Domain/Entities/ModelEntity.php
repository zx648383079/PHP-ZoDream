<?php
namespace Module\CMS\Domain\Entities;


use Domain\Entities\Entity;

/**
 * @property integer $id
 * @property string $name
 * @property string $table
 * @property integer $type
 * @property integer $position
 * @property integer $child_model
 * @property string $category_template
 * @property string $list_template
 * @property string $show_template
 * @property string $edit_template
 * @property string $setting
 */
class ModelEntity extends Entity {
    public static function tableName(): string {
        return 'cms_model';
    }

    protected function rules(): array {
        return [
            'name' => 'required|string:0,100',
            'table' => 'required|string:0,100',
            'type' => 'int:0,9',
            'position' => 'int:0,999',
            'child_model' => 'int',
            'category_template' => 'string:0,20',
            'list_template' => 'string:0,20',
            'show_template' => 'string:0,20',
            'edit_template' => 'string:0,20',
            'setting' => '',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => '名称',
            'table' => '表名',
            'type' => '类型',
            'position' => '排序',
            'child_model' => '分级模型',
            'category_template' => '分类模板',
            'list_template' => '列表模板',
            'show_template' => '详情模板',
            'edit_template' => '编辑模板',
            'setting' => 'Setting',
        ];
    }
}