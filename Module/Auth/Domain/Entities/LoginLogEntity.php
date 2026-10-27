<?php
namespace Module\Auth\Domain\Entities;

use Domain\Entities\Entity;

/**
 * Class LoginLogEntity
 * @property integer $id
 * @property string $ip
 * @property integer $user_id
 * @property string $user
 * @property integer $status
 * @property string $mode
 * @property integer $created_at
 */
class LoginLogEntity extends Entity {

    const MODE_WEB = 'web';
    const MODE_TICKET = 'ticket'; // 一次性凭证
    const MODE_APP = 'app';     // APP登陆
    const MODE_QR = 'qr';     // 扫描登陆
    const MODE_OAUTH = 'oauth';  //第三方登陆
    const MODE_WEBAUTHN = 'webauthn'; // passkey 登录

	public static function tableName(): string {
        return 'user_login_log';
    }

    protected function rules(): array {
        return [
            'ip' => 'required|string:0,120',
            'user_id' => 'int',
            'user' => 'string:0,100',
            'status' => 'int:0,127',
            'mode' => 'string:0,20',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'ip' => 'Ip',
            'user_id' => '用户',
            'user' => '登录账户',
            'status' => '状态',
            'mode' => '登陆方式',
            'created_at' => 'Created At',
        ];
    }

    
}