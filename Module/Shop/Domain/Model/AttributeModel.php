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
 * @property integer $search_type
 * @property integer $input_type
 * @property string $default_value
 * @property integer $position
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
            'search_type' => 'int:0,9',
            'input_type' => 'int:0,9',
            'default_value' => 'string:0,255',
            'position' => 'int:0,999',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'group_id' => 'Group Id',
            'type' => 'Type',
            'search_type' => 'Search Type',
            'input_type' => 'Input Type',
            'default_value' => 'Default Value',
            'position' => 'Position',
        ];
    }

    public function group() {
        return $this->hasOne(AttributeGroupModel::class, 'id', 'group_id');
    }

}