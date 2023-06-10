<?php
namespace Module\Shop\Domain\Entities;

use Domain\Entities\Entity;

/**
 * @property integer $id
 * @property string $code
 * @property string $setting
 * @property integer $status
 * @property integer $updated_at
 * @property integer $created_at
 */
class PluginEntity extends Entity {

    public static function tableName() {
        return 'shop_plugin';
    }

    protected function rules() {
        return [
            'code' => 'required|string:0,20',
            'setting' => 'required',
            'status' => 'int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'code' => 'Code',
            'setting' => 'Setting',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

}