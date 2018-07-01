<?php
namespace Module\OpenPlatform\Domain\Model;

use Domain\Model\Model;

class PlatformModel extends Model {

    public $type_list = [
        '网站',
        'APP',
    ];

    public $sign_type_list = [
        '不签名',
        'MD5',
        'HMAC',
        'RSA',
        'RSA2'
    ];

    public static function tableName() {
        return 'open_platform';
    }

    public function generateNewId() {
        $this->appid = time();
        $this->secret = '';
    }
}