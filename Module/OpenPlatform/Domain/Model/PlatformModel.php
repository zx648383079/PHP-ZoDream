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
 * @property string $description
 * @property string $appid
 * @property string $secret
 * @property integer $sign_type
 * @property string $sign_key
 * @property integer $encrypt_type
 * @property string $public_key
 * @property string $rules
 * @property integer $status
 * @property integer $allow_self
 * @property integer $created_at
 * @property integer $updated_at
 */
class PlatformModel extends Model {

    const STATUS_NONE = 0;
    const STATUS_WAITING = 9;
    const STATUS_SUCCESS = 1;

    public $type_list = [
        '网站',
        'APP',
        '小程序',
        '游戏',
    ];

    public $sign_type_list = [
        '不签名',
        'MD5',
        'HMAC',
    ];

    public $encrypt_type_list = [
        '不加密',
        'RSA',
        'RSA2',
        'DES'
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
            'sign_key' => 'string:0,32',
            'encrypt_type' => 'int:0,9',
            'public_key' => '',
            'rules' => '',
            'description' => '',
            'allow_self' => 'int',
            'status' => 'int:0,127',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'name' => '名称',
            'type' => '类型',
            'domain' => '域名',
            'appid' => 'App Id',
            'secret' => 'Secret',
            'sign_type' => '签名方式',
            'sign_key' => '签名密钥',
            'encrypt_type' => '加密方式',
            'public_key' => '加密公钥',
            'rules' => '允许规则',
            'status' => '状态',
            'description' => '介绍',
            'allow_self' => '允许手动生成',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function generateNewId() {
        $this->appid = '1'.time();
        $this->secret = md5(uniqid(md5(microtime(true)),true));
    }



    /**
     * @param $id
     * @return PlatformModel
     */
    public static function findByAppId($id) {
        return static::where('appid', $id)->first();
    }
}