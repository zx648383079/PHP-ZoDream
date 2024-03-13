<?php
namespace Module\Bot\Domain\Model;

use Module\Bot\Domain\Entities\BotEntity;

/**
 * 公众号数据
 * Class BotModel
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
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $user_id
 */
class BotSimpleModel extends BotEntity {

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
        'user_id',
        'created_at',
        'updated_at',
    ];

    public static function query() {
        return parent::query()->select(static::SIMPLE_MODE);
    }

}