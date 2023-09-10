<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 店铺
 * @property integer $id
 * @property string $name
 * @property integer $user_id
 * @property string $logo
 * @property string $description
 * @property string $address
 * @property integer $open_status
 * @property integer $status
 * @property integer $updated_at
 * @property integer $created_at
 */
class StoreEntity extends Entity {
    public static function tableName(): string {
        return 'eat_store';
    }

    protected function rules(): array {
        return [
            'name' => 'required|string',
            'user_id' => 'required|int',
            'logo' => 'string:0,255',
            'description' => 'string:0,255',
            'address' => 'string:0,255',
            'open_status' => 'int:0,127',
            'status' => 'int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'user_id' => 'User Id',
            'logo' => 'Logo',
            'description' => 'Description',
            'address' => 'Address',
            'open_status' => 'Open Status',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}