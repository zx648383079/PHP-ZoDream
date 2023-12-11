<?php
namespace Module\Disk\Domain\Model;


use Domain\Model\Model;
use Domain\Repositories\FileRepository;

/**
 * Class FileModel 文件数据
 * @package Domain\Model\Disk
 * @property integer $id
 * @property integer $name
 * @property string $extension 文件拓展名
 * @property string $md5 唯一的MD5值
 * @property string $location 服务器路径
 * @property integer $size 文件大小 字节
 * @property string $thumb 预览图
 * @property string $created_at 上传时间
 * @property integer $updated_at
 * @property string $download_url
 * @property integer $type
 */
class FileModel extends Model {

    const TYPE_IMAGE = 'image';
    const TYPE_DOCUMENT = 'doc';
    const TYPE_VIDEO = 'video';
    const TYPE_BT = 'bt';
    const TYPE_MUSIC = 'music';
    const TYPE_ZIP = 'archive';
    const TYPE_APP = 'app';
    const TYPE_UNKNOWN = 'unknown';

    public static function tableName(): string {
        return 'disk_file';
    }

    protected function rules(): array {
        return [
            'name' => 'required|string:0,100',
            'extension' => 'string:0,20',
            'md5' => 'required|string:0,32',
            'location' => 'required|string:0,200',
            'size' => 'int',
            'thumb' => 'string',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'extension' => 'Extension',
            'md5' => 'Md5',
            'location' => 'Location',
            'thumb' => 'string',
            'size' => 'Size',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function scopeOfType($query, $type) {
        return static::searchType($query, $type);
    }

    /**
     * 搜索类型
     * @param $query
     * @param $type
     * @return mixed
     */
    public static function searchType($query, $type) {
        $items = FileRepository::typeExtension($type);
        if (empty($items)) {
            return $query;
        }
        return $query->whereIn('extension', explode('|', $items));
    }

    public static function getType($extension) {
        foreach ([
            static::TYPE_DOCUMENT,
            static::TYPE_IMAGE,
            static::TYPE_BT,
            static::TYPE_APP,
            static::TYPE_MUSIC,
            static::TYPE_VIDEO,
            static::TYPE_ZIP,
                 ] as $key) {
            if (FileRepository::isTypeExtension($extension, $key)) {
                return $key;
            }
        }
        return self::TYPE_UNKNOWN;
    }

    public function getTypeAttribute() {
        return static::getType($this->extension);
    }

    public function getDownloadUrlAttribute() {
        return url('./download', ['id' => $this->id]);
    }
}