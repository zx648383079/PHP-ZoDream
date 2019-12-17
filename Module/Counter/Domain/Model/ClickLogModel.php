<?php
namespace Module\Counter\Domain\Model;

use Domain\Model\Model;

/**
 * Class ClickLogModel
 * @package Module\Counter\Domain\Model
 * @property integer $id
 * @property string $url
 * @property string $ip
 * @property string $session_id
 * @property string $user_agent
 * @property string $x
 * @property string $y
 * @property string $tag
 * @property string $tag_url
 * @property integer $created_at
 */
class ClickLogModel extends Model {

    public static function tableName() {
        return 'ctr_click_log';
    }

    protected function rules() {
        return [
            'url' => 'required|string:0,255',
            'ip' => 'required|string:0,120',
            'session_id' => 'string:0,30',
            'user_agent' => 'string:0,255',
            'x' => 'string:0,100',
            'y' => 'string:0,100',
            'tag' => 'required|string:0,120',
            'tag_url' => 'string:0,120',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'url' => 'Url',
            'ip' => 'Ip',
            'session_id' => 'Session Id',
            'user_agent' => 'User Agent',
            'x' => 'X',
            'y' => 'Y',
            'tag' => 'Tag',
            'tag_url' => 'Tag Url',
            'created_at' => 'Created At',
        ];
    }

}
