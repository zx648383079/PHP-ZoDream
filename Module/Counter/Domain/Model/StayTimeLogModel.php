<?php
namespace Module\Counter\Domain\Model;

use Domain\Model\Model;

/**
 * Class StayTimeLogModel
 * @package Module\Counter\Domain\Model
 * @property integer $id
 * @property string $url
 * @property string $ip
 * @property string $user_agent
 * @property string $session_id
 * @property integer $status
 * @property integer $enter_at
 * @property integer $leave_at
 */
class StayTimeLogModel extends Model {

    public static function tableName() {
        return 'ctr_stay_time_log';
    }

    protected function rules() {
        return [
            'url' => 'required|string:0,255',
            'ip' => 'required|string:0,120',
            'user_agent' => 'string:0,255',
            'session_id' => 'string:0,30',
            'status' => 'int:0,9',
            'enter_at' => 'int',
            'leave_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'url' => 'Url',
            'ip' => 'Ip',
            'user_agent' => 'User Agent',
            'session_id' => 'Session Id',
            'status' => 'Status',
            'enter_at' => 'Enter At',
            'leave_at' => 'Leave At',
        ];
    }
}
