<?php
namespace Module\OpenPlatform\Domain\Model;

use Domain\Model\Model;
use Module\OpenPlatform\Domain\Hmac;
use Zodream\Database\Model\UserModel;
use Zodream\Helpers\Arr;
use Zodream\Helpers\Str;
use Zodream\Infrastructure\Http\Output\RestResponse;

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
class PlatformSimpleModel extends PlatformModel {

    public static function query() {
        return parent::query()->select('id', 'name', 'type', 'description');
    }
}