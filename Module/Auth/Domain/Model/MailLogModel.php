<?php
namespace Module\Auth\Domain\Model;


use Domain\Model\Model;

/**
 * Class MailLogModel
 * @package Module\Auth\Domain\Model
 * @property integer $id
 * @property string $ip
 * @property integer $user_id
 * @property integer $type
 * @property string $code
 * @property integer $amount
 * @property integer $created_at
 */
class MailLogModel extends Model {

    const TYPE_FIND = 1;

    public $timestamps = false;

    public static function tableName() {
        return 'user_mail_log';
    }

    protected function rules() {
        return [
            'ip' => 'required|string:0,120',
            'user_id' => 'required|int',
            'type' => 'int:0,127',
            'code' => 'required|string:0,40',
            'amount' => 'int:0,127',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'ip' => 'Ip',
            'user_id' => 'User Id',
            'type' => 'Type',
            'code' => 'Code',
            'amount' => 'Amount',
            'created_at' => 'Created At',
        ];
    }
}