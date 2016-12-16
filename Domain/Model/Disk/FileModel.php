<?php
namespace Domain\Model\Disk;

use Domain\Model\Model;

/**
 * Class FileModel 文件数据
 * @package Domain\Model\Disk
 * @property integer $id
 * @property string $md5 唯一的MD5值
 * @property string $location 服务器路径
 * @property string $size 文件大小 字节
 * @property string $create_at 上传时间
 */
class FileModel extends Model {

    public static function tableName() {
        return 'file';
    }
}