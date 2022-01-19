<?php
namespace Module\WeChat\Domain\Model;

use Module\WeChat\Domain\Entities\WeChatEntity;
use Zodream\ThirdParty\WeChat\BaseWeChat;
use Zodream\ThirdParty\WeChat\Message;
use Zodream\ThirdParty\WeChat\User;

/**
 * 公众号数据
 * Class WeChatModel
 * @property integer $id
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
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $user_id
 */
class WeChatModel extends WeChatEntity {
    /**
     * 未激活状态
     */
    const STATUS_INACTIVE = 4;
    /**
     * 激活状态
     */
    const STATUS_ACTIVE = 5;
    /**
     * 删除状态
     */
    const STATUS_DELETED = 0;
    /**
     * 普通订阅号
     */
    const TYPE_SUBSCRIBE = 0;
    /**
     * 认证订阅号
     */
    const TYPE_SUBSCRIBE_VERIFY = 1;
    /**
     * 普通服务号
     */
    const TYPE_SERVICE = 2;
    /**
     * 认证服务号
     */
    const TYPE_SERVICE_VERIFY = 3;
    /**
     * 公众号类型列表
     * @var array
     */
    public static $types = [
        self::TYPE_SUBSCRIBE => '订阅号',
        self::TYPE_SUBSCRIBE_VERIFY => '认证订阅号',
        self::TYPE_SERVICE_VERIFY => '认证服务号',
    ];
    public static $statuses = [
        self::STATUS_INACTIVE => '未接入',
        self::STATUS_ACTIVE => '已接入',
        self::STATUS_DELETED => '已删除'
    ];



    public function attributeHints() {
        return [
            'api_url' => '请复制该内容填写到微信后台->开发者中心->服务器配置并确定Token和EncodingAesKey和微信后台的设置保持一致.'
        ];
    }

    /**
     * 返回公众号微信接口链接
     * @return string
     */
    public function getApiUrlAttribute() {
        return url('./message/id/'.$this->id);
    }

    public function getTypeLabelAttribute() {
        return self::$types[$this->type];
    }

    public function getStatusLabelAttribute() {
        return self::$statuses[$this->status];
    }

    public function parseConfigs() {
        return [
            'appid' => $this->appid,
            'secret' => $this->secret,
            'aes_key' => $this->aes_key,
            'token' => $this->token
        ];
    }

    /**
     * 注入sdk
     * @param string|mixed $instance
     * @return BaseWeChat|Message|User|mixed
     * @throws \Exception
     */
    public function sdk($instance) {
        if ($instance instanceof BaseWeChat) {
            return $instance->set($this->parseConfigs());
        }
        if (is_string($instance) && class_exists($instance)) {
            return new $instance($this->parseConfigs());
        }
        throw new \Exception('sdk is error');
    }

}