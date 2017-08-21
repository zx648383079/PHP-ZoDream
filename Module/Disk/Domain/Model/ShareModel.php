<?php
namespace Module\Disk\Domain\Model;

use Zodream\Database\Model\Model;

/**
 * Class ShareModel
 * @package Domain\Model\Disk
 * @property integer $id
 * @property integer $disk_id
 * @property string $name
 * @property integer $mode 分享模式
 * @property string $password
 * @property integer $user_id
 * @property integer $death_at 过期时间
 * @property integer $view_count 查看人数
 * @property integer $down_count 下载人数
 * @property integer $create_at
 */
class ShareModel extends Model {

    const SHARE_PUBLIC = 0; //公开分享
    const SHARE_PROTECTED = 1; //密码分享
    const SHARE_PRIVATE = 2;  //分享给个人

    public static function tableName() {
        return 'share';
    }


}