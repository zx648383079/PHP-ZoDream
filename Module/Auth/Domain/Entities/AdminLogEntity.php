<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Entities;

use Domain\Entities\Entity;
use Module\Auth\Domain\Model\UserSimpleModel;

/**
 * Class AdminLogEntity
 * @property integer $id
 * @property string $ip
 * @property integer $user_id
 * @property integer $item_type
 * @property integer $item_id
 * @property string $action
 * @property string $remark
 * @property integer $created_at
 */
class AdminLogEntity extends Entity {

	public static function tableName(): string {
        return 'user_admin_log';
    }

    protected function rules(): array {
        return [
            'ip' => 'required|string:0,120',
            'user_id' => 'required|int',
            'item_type' => 'int:0,127',
            'item_id' => 'int',
            'action' => 'required|string:0,30',
            'remark' => 'string:0,255',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'ip' => 'Ip',
            'user_id' => 'User Id',
            'item_type' => 'Item Type',
            'item_id' => 'Item Id',
            'action' => 'Action',
            'remark' => 'Remark',
            'created_at' => 'Created At',
        ];
    }

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

}