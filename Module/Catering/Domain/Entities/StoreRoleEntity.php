<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 店铺员工权限
 * @property integer $id
 * @property integer $store_id
 * @property integer $name
 * @property string $description
 * @property string $action
 * @property integer $updated_at
 * @property integer $created_at
 */
class StoreRoleEntity extends Entity {
    public static function tableName() {
        return 'eat_store_role';
    }

    protected function rules() {
        return [
            'store_id' => 'required|int',
            'name' => 'required|int',
            'description' => 'string:0,255',
            'action' => 'string:0,255',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'store_id' => 'Store Id',
            'name' => 'Name',
            'description' => 'Description',
            'action' => 'Action',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

}