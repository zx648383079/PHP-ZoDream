<?php
namespace Module\CMS\Domain\Entities;


use Domain\Entities\Entity;

/**
 * @property integer $id
 * @property string $name
 * @property string $field
 * @property integer $model_id
 * @property string $type
 * @property integer $length
 * @property integer $position
 * @property integer $form_type
 * @property integer $is_main
 * @property integer $is_required
 * @property integer $is_search
 * @property integer $is_default
 * @property integer $is_system
 * @property integer $is_disable
 * @property string $match
 * @property string $tip_message
 * @property string $error_message
 * @property string $tab_name
 * @property string $setting
 */
class ModelFieldEntity extends Entity {
    public static function tableName(): string {
        return 'cms_model_field';
    }

    protected function rules(): array {
        return [
            'name' => 'required|string:0,100',
            'field' => 'required|string:0,100',
            'model_id' => 'required|int',
            'type' => 'string:0,20',
            'length' => 'int:0,999',
            'position' => 'int:0,999',
            'form_type' => 'int:0,999',
            'is_main' => 'bool',
            'is_required' => 'bool',
            'is_search' => 'bool',
            'is_default' => 'bool',
            'is_disable' => 'bool',
            'is_system' => 'bool',
            'match' => 'string:0,255',
            'tip_message' => 'string:0,255',
            'error_message' => 'string:0,255',
            'tab_name' => 'string:0,4',
            'setting' => '',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => '名称',
            'field' => '字段名',
            'model_id' => 'Model Id',
            'type' => '类型',
            'is_main' => '主表',
            'length' => '字段长度',
            'position' => '排序',
            'form_type' => '表单类型',
            'is_required' => '是否必填',
            'is_search' => '是否搜索',
            'is_default' => '默认值',
            'is_system' => '系统字段',
            'match' => '匹配规则',
            'tip_message' => '提示信息',
            'error_message' => '错误提示',
            'setting' => '其他设置',
        ];
    }
}