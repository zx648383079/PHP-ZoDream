<?php
namespace Module\WeChat\Domain\Model;

use Module\WeChat\Domain\Entities\WeChatEntity;

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
 */
class WeChatSimpleModel extends WeChatEntity {

    const SIMPLE_MODE = [
        'id',
        'name',
        'account',
        'original',
        'type',
        'avatar',
        'qrcode',
        'address',
        'description',
        'created_at',
        'updated_at',
    ];

    public static function query() {
        return parent::query()->select(static::SIMPLE_MODE);
    }

}