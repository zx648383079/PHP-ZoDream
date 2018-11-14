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
 * @property string $thumb 预览图
 * @property string $created_at 上传时间
 * @property integer $updated_at
 * @property string $download_url
 * @property integer $type
 */
class FileModel extends Model {

    const TYPE_IMAGE = 1;
    const TYPE_DOCUMENT = 2;
    const TYPE_VIDEO = 3;
    const TYPE_BT = 4;
    const TYPE_MUSIC = 5;
    const TYPE_ZIP = 6;
    const TYPE_APP = 7;
    const TYPE_UNKNOW = 0;

    public static $extensionMaps = [
        self::TYPE_IMAGE => [
            'png', 'jpg', 'jpeg'
        ],
        self::TYPE_DOCUMENT => [
            'doc', 'docs', 'txt'
        ],
        self::TYPE_VIDEO => [
            'avi', 'mp4', 'rmvb'
        ],
        self::TYPE_BT => [
            'torrent'
        ],
        self::TYPE_MUSIC => [
            'mp3', 'wav', 'ape', 'flac'
        ],
        self::TYPE_ZIP => [
            'rar', 'zip', '7z'
        ],
        self::TYPE_APP => [
            'ipa', 'apk', 'appx'
        ]
    ];

    public static function tableName() {
        return 'disk_file';
    }

    protected function rules() {
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

    protected function labels() {
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
        switch ($type) {
            case self::TYPE_IMAGE:
                return $query->whereIn('extension', self::$extensionMaps[self::TYPE_IMAGE]);
            case self::TYPE_DOCUMENT:
                return $query->whereIn('extension', self::$extensionMaps[self::TYPE_DOCUMENT]);
            case self::TYPE_VIDEO:
                return $query->whereIn('extension', self::$extensionMaps[self::TYPE_VIDEO]);
            case self::TYPE_MUSIC:
                return $query->whereIn('extension', self::$extensionMaps[self::TYPE_MUSIC]);
            case self::TYPE_BT:
                return $query->whereIn('extension', self::$extensionMaps[self::TYPE_BT]);
            case self::TYPE_ZIP:
                return $query->whereIn('extension', self::$extensionMaps[self::TYPE_ZIP]);
            case self::TYPE_APP:
                return $query->whereIn('extension', self::$extensionMaps[self::TYPE_APP]);
            default:
                return $query;
        }
    }

    public static function getType($extension) {
        foreach (self::$extensionMaps as $key => $maps) {
            if (in_array($extension, $maps)) {
                return $key;
            }
        }
        return self::TYPE_UNKNOW;
    }

    public function getTypeAttribute() {
        return static::getType($this->extension);
    }

    public function getDownloadUrlAttribute() {
        return url('./download', ['id' => $this->id]);
    }
}