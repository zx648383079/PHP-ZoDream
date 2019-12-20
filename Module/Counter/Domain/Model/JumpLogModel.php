<?php
namespace Module\Counter\Domain\Model;

use Domain\Model\Model;

/**
 * Class ClickLogModel
 * @package Module\Counter\Domain\Model
 * @property integer $id
 * @property string $referrer
 * @property string $url
 * @property string $ip
 * @property string $session_id
 * @property string $user_agent
 * @property integer $created_at
 */
class JumpLogModel extends Model {

    public static function tableName() {
        return 'ctr_jump_log';
    }

    protected function rules() {
        return [
            'referrer' => 'required|string:0,255',
            'url' => 'required|string:0,255',
            'ip' => 'required|string:0,120',
            'session_id' => 'string:0,30',
            'user_agent' => 'string:0,255',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'referrer' => 'Referrer',
            'url' => 'Url',
            'ip' => 'Ip',
            'session_id' => 'Session Id',
            'user_agent' => 'User Agent',
            'created_at' => 'Created At',
        ];
    }

}
