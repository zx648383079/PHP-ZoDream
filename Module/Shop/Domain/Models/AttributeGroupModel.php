<?php
namespace Module\Shop\Domain\Models;


use Domain\Model\Model;

/**
 * Class AttributeGroupModel
 * @package Module\Shop\Domain\Model
 * @property integer $id
 * @property string $name
 * @property integer $created_at
 * @property integer $updated_at
 */
class AttributeGroupModel extends Model {

    public static function tableName() {
        return 'shop_attribute_group';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,30',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => '名称',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}