<?php
declare(strict_types=1);
namespace Module\OnlineService\Domain\Models;


use Domain\Model\Model;

/**
 * Class SessionLogModel
 * @package Module\OnlineService\Domain\Models
 * @property integer $id
 * @property integer $user_id
 * @property integer $session_id
 * @property string $remark
 * @property integer $status
 * @property integer $created_at
 */
class SessionLogModel extends Model {
    public static function tableName() {
        return 'service_session_log';
    }

    protected function rules() {
        return [
            'user_id' => 'required|int',
            'session_id' => 'required|int',
            'remark' => 'string:0,255',
            'status' => 'int:0,127',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'session_id' => 'Session Id',
            'remark' => 'Remark',
            'status' => 'Status',
            'created_at' => 'Created At',
        ];
    }
}