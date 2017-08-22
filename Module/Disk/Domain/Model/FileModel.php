<?php
namespace Module\Disk\Domain\Model;


use Zodream\Database\Model\Model;

/**
 * Class FileModel 文件数据
 * @package Domain\Model\Disk
 * @property integer $id
 * @property integer $name
 * @property string $extension 文件拓展名
 * @property string $md5 唯一的MD5值
 * @property string $location 服务器路径
 * @property integer $size 文件大小 字节
 * @property string $created_at 上传时间
 */
class FileModel extends Model {

    public static function tableName() {
        return 'file';
    }
}