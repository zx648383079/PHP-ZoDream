<?php
namespace Module\OpenPlatform\Domain\Model;

use Domain\Model\Model;

class PlatformModel extends Model {

    public $type_list = [
        0 => '网站',
        1 => 'APP',
    ];

    public $sign_type_list = [
        0 => '不签名',
        1 => 'MD5',
        2 => 'RSA',
        3 => 'RSA2'
    ];

    public static function tableName() {
        return 'platform';
    }

    public function generateNewId() {
        $this->appid = '';
        $this->secret = '';
    }
}