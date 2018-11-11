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
            'name' => '名称',
            'group_id' => '分组',
            'type' => '类型',
            'search_type' => '搜索类型',
            'input_type' => '输入类型',
            'default_value' => '默认值',
            'position' => '排序',
        ];
    }

    public function group() {
        return $this->hasOne(AttributeGroupModel::class, 'id', 'group_id');
    }

}