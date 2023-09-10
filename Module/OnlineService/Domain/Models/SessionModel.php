<?php
declare(strict_types=1);
namespace Module\OnlineService\Domain\Models;


use Domain\Model\Model;
use Module\Auth\Domain\Model\UserSimpleModel;

/**
 * Class SessionModel
 * @package Module\OnlineService\Domain\Models
 * @property integer $id
 * @property string $name
 * @property string $remark
 * @property integer $user_id
 * @property integer $service_id
 * @property string $ip
 * @property string $user_agent
 * @property integer $status
 * @property integer $service_word
 * @property integer $updated_at
 * @property integer $created_at
 */
class SessionModel extends Model {
    public static function tableName(): string {
        return 'service_session';
    }

    protected function rules(): array {
        return [
            'user_id' => 'int',
            'service_id' => 'int',
            'ip' => 'required|string:0,120',
            'user_agent' => 'required|string:0,255',
            'name' => 'string:0,20',
            'remark' => 'string:0,255',
            'status' => 'int:0,127',
            'service_word' => 'int',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'service_id' => 'Service Id',
            'ip' => 'Ip',
            'user_agent' => 'User Agent',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }
}