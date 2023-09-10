<?php
namespace Module\WeChat\Domain\Entities;

use Domain\Entities\Entity;

/**
 * Class WeChatEntity
 * @package Module\WeChat\Domain\Entities
 * @property integer $id
 * @property integer $platform_type
 * @property string $name
 * @property string $token
 * @property string $access_token
 * @property string $account
 * @property string $original
 * @property integer $type
 * @property string $appid
 * @property string $secret
 * @property string $aes_key
 * @property string $avatar
 * @property string $qrcode
 * @property string $address
 * @property string $description
 * @property string $username
 * @property string $password
 * @property integer $status
 * @property integer $updated_at
 * @property integer $created_at
 * @property integer $user_id
 */
class WeChatEntity extends Entity {

    public static function tableName(): string {
        return 'wechat';
    }

    protected function rules(): array {
        return [
            'name' => 'required|string:0,40',
            'token' => 'required|string:0,32',
            'access_token' => 'string:0,255',
            'account' => 'required|string:0,30',
            'original' => 'required|string:0,40',
            'type' => 'int:0,127',
            'appid' => 'required|string:0,50',
            'secret' => 'required|string:0,50',
            'aes_key' => 'string:0,43',
            'avatar' => 'string:0,255',
            'qrcode' => 'string:0,255',
            'address' => 'string:0,255',
            'description' => 'string:0,255',
            'username' => 'string:0,40',
            'password' => 'string:0,32',
            'status' => 'int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
            'user_id' => 'required|int',
        ];
    }

    /**
     * @inheritdoc
     */
    public function labels(): array {
        return [
            'id' => '公众号ID',
            'name' => '公众号名称',
            'token' => '微信服务Token(令牌)',
            'access_token' => 'AccessToken(访问令牌)',
            'account' => '微信号',
            'original' => '原始ID',
            'type' => '公众号类型',
            'appid' => 'AppID(应用ID)',
            'secret' => 'AppSecret(应用密钥)',
            'aes_key' => '消息加密秘钥EncodingAesKey',
            'avatar' => '头像地址',
            'qrcode' => '二维码地址',
            'address' => '所在地址',
            'description' => '公众号简介',
            'username' => '微信官网登录名(邮箱)',
            'status' => '状态',
            'password' => '微信官网登录密码',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
            'user_id' => 'User Id',
            'api_url' => 'API地址'
        ];
    }
}