<?php
namespace Module\Disk\Domain\Model;

use Zodream\Database\Model\Model;

/**
 * Class ShareUserModel
 * @package Domain\Model\Disk
 * @property integer $id
 * @property integer $share_id
 * @property integer $disk_id
 */
class ShareFileModel extends Model {

    public static function tableName() {
        return 'share_file';
    }
}