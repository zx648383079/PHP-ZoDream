<?php
namespace Module\Auth\Domain\Model;


use Domain\Model\Model;

/**
 * Class PermissionModel
 * @package Module\Auth\Domain\Model
 * @property integer $id
 * @property string $name
 * @property string $display_name
 * @property string $description
 * @property integer $created_at
 * @property integer $updated_at
 */
class PermissionModel extends Model {
    public static function tableName() {
        return 'permission';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,40',
            'display_name' => 'string:0,100',
            'description' => 'string:0,255',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'display_name' => 'Display Name',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}