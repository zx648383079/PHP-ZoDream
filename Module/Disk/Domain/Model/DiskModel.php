<?php
namespace Module\Disk\Domain\Model;

use Zodream\Database\Model\Model;
use Zodream\Helpers\Time;

/**
 * Class DiskModel 网盘目录数据
 * @package Domain\Model\Disk
 * @property integer $id
 * @property integer $user_id
 * @property integer $file_id 默认为文件名
 * @property string $name 文件名
 * @property integer $left_id 左值
 * @property integer $right_id 右值
 * @property integer $parent_id 上级
 * @property integer $deleted_at
 * @property integer $updated_at
 * @property integer $created_at
 */
class DiskModel extends Model {

    const TYPE_IMAGE = 1;
    const TYPE_DOCUMENT = 2;
    const TYPE_VIDEO = 3;
    const TYPE_BT = 4;
    const TYPE_MUSIC = 5;
    const TYPE_ZIP = 6;

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
        ]
    ];


    public static function tableName() {
        return 'disk';
    }

    public function scopeOfType($query, $type) {
        switch ($type) {
            case self::TYPE_IMAGE:
                return $query->andWhere(['extension', 'in', self::$extensionMaps[self::TYPE_IMAGE]]);
            case self::TYPE_DOCUMENT:
                return $query->andWhere(['extension', 'in', self::$extensionMaps[self::TYPE_DOCUMENT]]);
            case self::TYPE_VIDEO:
                return $query->andWhere(['extension', 'in', self::$extensionMaps[self::TYPE_VIDEO]]);
            case self::TYPE_MUSIC:
                return $query->andWhere(['extension', 'in', self::$extensionMaps[self::TYPE_MUSIC]]);
            case self::TYPE_BT:
                return $query->andWhere(['extension', 'in', self::$extensionMaps[self::TYPE_BT]]);
            case self::TYPE_ZIP:
                return $query->andWhere(['extension', 'in', self::$extensionMaps[self::TYPE_ZIP]]);
            default:
                return $query;
        }
    }

    public function moveTo(DiskModel $disk) {
        if ($disk->left_id > $this->left_id
            && $disk->right_id < $this->right_id) {
            // 目标为子节点无法移动
            return false;
        }
        // 更改目标上级的左右值
        // 移动并更改左右值
        $difLeft = $disk->right_id - 1 - $this->left_id;
        $this->updateOne([
            'left_id',
            'right_id',
        ], [
            'user_id' => $this->user_id,
            'left_id > '.$this->left_id,
            'right_id < '.$this->right_id
        ], $difLeft);
        $this->left_id += $difLeft;
        $this->right_id += $difLeft;
        $this->parent_id = $disk->id;
        $this->save();

        // 更改原上级的左右值
        $this->updateOne('right_id', [
            'user_id' => $this->user_id,
            'left_id < '.$this->left_id,
            'right_id > '.$this->right_id
        ], $this->left_id - $this->right_id - 1);
    }

    public function copyTo(DiskModel $disk) {
        // 更改目标上级的右值
        $this->updateOne('right_id', [
            'user_id' => $disk->user_id,
            'left_id <= '.$disk->left_id,
            'right_id >= '.$disk->right_id
        ], $this->right_id - $this->left_id + 1);
        // 复制并更改左右值
        //
    }

    public function deleteThis() {
        $this->delete([
            'user_id' => $this->user_id,
            'left_id >= '.$this->left_id,
            'right_id <= '.$this->right_id
        ]);
        $num = $this->left_id - $this->right_id - 1;
        $this->updateOne('right_id', [
            'user_id' => $this->user_id,
            'right_id > '.$this->right_id
        ], $num);
        $this->updateOne('left_id', [
            'user_id' => $this->user_id,
            'left_id > '.$this->right_id,
        ], $num);
    }

    /**
     * @return static[]
     */
    public function getChildren() {
        return static::findAll(['parent_id' => $this->id]);
    }

    public function addFirstChild(DiskModel $model) {
        $this->updateOne('left_id', [
            'user_id' => $this->user_id,
            'left_id > '.$this->left_id
        ], 2);
        $this->updateOne('right_id', [
            'user_id' => $this->user_id,
            'right_id > '.$this->right_id
        ], 2);
        $model->parent_id = $this->id;
        $model->left_id = $this->left_id + 1;
        $model->right_id = $this->right_id + 2;
        return $model->save();
    }
    public function addLastChild(DiskModel $model) {
        $this->updateOne('left_id', [
            'user_id' => $this->user_id,
            'left_id >= '.$this->right_id
        ], 2);
        $this->updateOne('right_id', [
            'user_id' => $this->user_id,
            'right_id >= '.$this->right_id
        ], 2);
        $model->parent_id = $this->id;
        $model->left_id = $this->right_id - 1;
        $model->right_id = $this->right_id;
        return $model->save();
    }

    /**
     * 后面追加
     * @param DiskModel $model
     * @return bool|int
     */
    public function append(DiskModel $model) {
        $this->updateOne('left_id', [
            'user_id' => $this->user_id,
            'left_id > '.$this->right_id
        ], 2);
        $this->updateOne('right_id', [
            'user_id' => $this->user_id,
            'right_id > '.$this->right_id
        ], 2);
        $model->parent_id = $this->id;
        $model->left_id = $this->right_id + 1;
        $model->right_id = $this->right_id + 2;
        return $model->save();
    }

    public function prepend(DiskModel $model) {
        $this->updateOne('left_id', [
            'user_id' => $this->user_id,
            'left_id >= '.$this->left_id
        ], 2);
        $this->updateOne('right_id', [
            'user_id' => $this->user_id,
            'right_id >= '.$this->left_id
        ], 2);
        $model->parent_id = $this->id;
        $model->left_id = $this->left_id;
        $model->right_id = $this->right_id;
        return $model->save();
    }


    public function getCount() {
        return ($this->right_id - $this->left_id - 1) / 2;
    }

    public function getDeletedAtAttribute() {
        $val = $this->getAttributeSource('deleted_at');
        return empty($val) ? '' : Time::format($val);
    }
}