<?php
namespace Module\WeChat\Domain\Model;

use Domain\Model\Model;
use Zodream\ThirdParty\WeChat\User;

/**
 * Class QrcodeModel
 * @package Module\WeChat\Domain\Model
 * @property integer $id
 * @property integer $wid
 * @property integer $type
 * @property string $scene_str
 * @property integer $scene_id
 * @property integer $expire_time
 * @property string $qr_url
 * @property string $url
 * @property string $name
 * @property integer $updated_at
 * @property integer $created_at
 */
class QrcodeModel extends Model {

    public static function tableName() {
        return 'wechat_qrcode';
    }

    protected function rules() {
        return [
            'wid' => 'required|int',
            'name' => 'required|string:0,255',
            'type' => 'int:0,127',
            'scene_str' => 'string:0,255',
            'scene_id' => 'int',
            'expire_time' => 'int',
            'qr_url' => 'string:0,255',
            'url' => 'string:0,255',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'wid' => 'Wid',
            'name' => 'Name',
            'type' => 'Type',
            'scene_str' => 'Scene Str',
            'scene_id' => 'Scene Id',
            'expire_time' => 'Expire Time',
            'qr_url' => 'Qr Url',
            'url' => 'Url',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

}