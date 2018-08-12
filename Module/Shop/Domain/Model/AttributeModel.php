<?php
namespace Module\Shop\Domain\Model;


use Domain\Model\Model;

/**
 * Class AttributeModel
 * @package Module\Shop\Domain\Model
 * @property integer $id
 * @property string $name
 * @property integer $group_id
 * @property integer $type
 * @property integer $readonly
 * @property integer $input_type
 * @property string $default_value
 */
class AttributeModel extends Model {

    public static function tableName() {
        return 'shop_attribute';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,30',
            'group_id' => 'required|int',
            'type' => 'int:0,9',
            'readonly' => 'int:0,9',
            'input_type' => 'int:0,9',
            'default_value' => 'required|string:0,255',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'group_id' => 'Group Id',
            'type' => 'Type',
            'readonly' => 'Readonly',
            'input_type' => 'Input Type',
            'default_value' => 'Default Value',
        ];
    }

}