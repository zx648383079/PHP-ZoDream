<?php
namespace Module\OpenPlatform\Domain\Model;

use Domain\Model\Model;

/**
 * Class PlatformModel
 * @package Module\OpenPlatform\Domain\Model
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property integer $type
 * @property string $domain
 * @property string $appid
 * @property string $secret
 * @property integer $sign_type
 * @property string $public_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
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


    protected function rules() {
        return [
            'user_id' => 'required|int',
            'name' => 'required|string:0,20',
            'type' => 'int:0,9',
            'domain' => 'required|string:0,50',
            'appid' => 'required|string:0,12',
            'secret' => 'required|string:0,32',
            'sign_type' => 'int:0,9',
            'public_key' => '',
            'status' => 'int:0,9',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'name' => 'Name',
            'type' => 'Type',
            'domain' => 'Domain',
            'appid' => 'Appid',
            'secret' => 'Secret',
            'sign_type' => 'Sign Type',
            'public_key' => 'Public Key',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function generateNewId() {
        $this->appid = time();
        $this->secret = '';
    }

    public function sign(array $data) {
        return '';
    }

    public function verify(array $data, $sign) {
        return true;
    }
}