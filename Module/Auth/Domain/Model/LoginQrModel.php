<?php
namespace Module\Auth\Domain\Model;

use Domain\Model\Model;

/**
 * Class LoginQrModel
 * @package Module\Auth\Domain\Model
 * @property integer $id
 * @property integer $user_id
 * @property string $token
 * @property integer $status
 * @property integer $expired_at
 * @property integer $created_at
 */
class LoginQrModel extends Model {

    const STATUS_UN_SCAN = 0;  //未扫码
    const STATUS_UN_CONFIRM = 1;  // 已扫码待确认
    const STATUS_SUCCESS = 2;     // 登录成功
    const STATUS_REJECT = 3;      // 拒绝登录

    public static function tableName() {
        return 'user_login_qr';
    }

    protected function rules() {
        return [
            'user_id' => 'int',
            'token' => 'required|string:0,32',
            'status' => 'int:0,9',
            'expired_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'token' => 'Token',
            'status' => 'Status',
            'expired_at' => 'Expired At',
            'created_at' => 'Created At',
        ];
    }
}