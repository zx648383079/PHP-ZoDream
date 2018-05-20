<?php
namespace Module\Disk\Domain\Model;

use Zodream\Database\Model\Model;

/**
 * Class ShareUserModel
 * @package Domain\Model\Disk
 * @property integer $id
 * @property integer $share_id
 * @property integer $user_id
 */
class ShareUserModel extends Model {

    public static function tableName() {
        return 'disk_share_user';
    }

    protected function rules() {
        return [
            'user_id' => 'required|int',
            'share_id' => 'required|int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'share_id' => 'Share Id',
        ];
    }
}