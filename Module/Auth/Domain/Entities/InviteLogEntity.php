<?php
namespace Module\Auth\Domain\Entities;

use Domain\Entities\Entity;
/**
 * 邀请记录
 * @property integer $id
 * @property integer $user_id
 * @property integer $parent_id
 * @property integer $created_at
 * @property integer $code_id
 * @property integer $status
 * @property integer $updated_at
 */
class InviteLogEntity extends Entity {

    public static function tableName(): string {
        return 'user_invite_log';
    }

    protected function rules(): array {
        return [
            'user_id' => 'required|int',
            'parent_id' => 'int',
            'created_at' => 'int',
            'code_id' => 'int',
            'status' => 'int:0,127',
            'updated_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'parent_id' => 'Parent Id',
            'created_at' => 'Created At',
            'code_id' => 'Code Id',
            'status' => 'Status',
            'updated_at' => 'Updated At',
        ];
    }

}