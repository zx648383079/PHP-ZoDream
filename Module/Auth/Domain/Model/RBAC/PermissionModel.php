<?php
namespace Module\Auth\Domain\Model\RBAC;


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
    public static function tableName(): string {
        return 'rbac_permission';
    }

    protected function rules(): array {
        return [
            'name' => 'required|string:0,40',
            'display_name' => 'string:0,100',
            'description' => 'string:0,255',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => '权限名',
            'display_name' => '别名',
            'description' => '说明',
            'created_at' => '注册时间',
            'updated_at' => '更新时间',
        ];
    }
}