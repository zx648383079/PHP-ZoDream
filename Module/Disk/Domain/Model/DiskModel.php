<?php
namespace Module\Disk\Domain\Model;

use Domain\Model\Model;
use Zodream\Database\Command;

use Zodream\Helpers\Time;
use Zodream\Database\Model\Query;

/**
 * Class DiskModel 网盘目录数据
 * @package Domain\Model\Disk
 * @property integer $id
 * @property integer $user_id
 * @property integer $file_id 默认为文件名
 * @property string $name 文件名
 * @property string $extension
 * @property integer $left_id 左值
 * @property integer $right_id 右值
 * @property integer $parent_id 上级
 * @property integer $deleted_at
 * @property integer $updated_at
 * @property integer $created_at
 * @property FileModel $file
 * @method Query auth();
 *
 */
class DiskModel extends Model {

    public static function tableName(): string {
        return 'disk';
    }

    protected function rules(): array {
        return [
            'name' => 'required|string:0,100',
            'extension' => 'required|string:0,20',
            'file_id' => 'int',
            'user_id' => 'int',
            'left_id' => 'int',
            'right_id' => 'int',
            'parent_id' => 'int',
            'deleted_at' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'file_id' => 'File Id',
            'user_id' => 'User Id',
            'left_id' => 'Left Id',
            'right_id' => 'Right Id',
            'parent_id' => 'Parent Id',
            'deleted_at' => 'Deleted At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function file() {
        return $this->hasOne(FileModel::class, 'id', 'file_id');
    }

    public function scopeAuth($query) {
        return $query->where('user_id', auth()->id());
    }

    public function moveTo(DiskModel $disk) {
        if ($this->parent_id = $disk->id) {
            return false;
        }
        if ($disk->left_id > $this->left_id
            && $disk->right_id < $this->right_id) {
            // 目标为子节点无法移动
            return false;
        }
        // 更改目标上级的左右值
        // 移动并更改左右值
        $difLeft = $disk->right_id - 1 - $this->left_id;
        $this->where('user_id', $this->user_id)
            ->where('left_id', '>', $this->left_id)
            ->where('right_id', '<', $this->right_id)
            ->updateIncrement([
            'left_id',
            'right_id',
        ], $difLeft);
        $this->left_id += $difLeft;
        $this->right_id += $difLeft;
        $this->parent_id = $disk->id;
        $this->save();

        // 更改原上级的左右值
        $this->where('user_id', $this->user_id)
            ->where('left_id', '<', $this->left_id)
            ->where('right_id', '>', $this->right_id)
            ->updateIncrement('right_id', $this->left_id - $this->right_id - 1);
        return true;
    }

    public function copyTo(DiskModel $disk) {
        if ($disk->file_id > 0) {
            return false;
        }
        if ($this->user_id == $disk->user_id && (
                $this->parent_id = $disk->id  // 目标为父节点
                || $disk->left_id > $this->left_id && $disk->right_id < $this->right_id // 目标为子节点无法移动
            ) ) {
            return false;
        }
        // 先空出位置
        $diff = $this->right_id - $this->left_id + 1;
//        self::where('user_id', $disk->user_id)
//            ->where('left_id', '>=', $disk->right_id)
//            ->updateIncrement('left_id', $diff);
//        $disk->left_id += $diff;
        // 起始的左值
        $left = $disk->left_id < $this->right_id ? $disk->right_id : $disk->left_id + 1;
        if ($disk->left_id < $disk->right_id) {
            // 排除临时创造的错误值
            self::where('user_id', $disk->user_id)
                ->where('right_id', '>=', $disk->right_id)
                ->updateIncrement('right_id', $diff);
            $disk->right_id += $diff;
        } else {
            // 假的就更新左值方便下一次用
            $disk->left_id = $left + $diff + 1;
        }
        if ($this->file_id > 0) {
            DiskModel::create([
                'name' => $this->name,
                'file_id' => $this->file_id,
                'user_id' => $disk->user_id,
                'left_id' => $left,
                'right_id' => $left + 1,
                'parent_id' => $disk->id,
            ]);
            return true;
        }
        // 复制开始
        $real_diff = $left - $this->left_id;
        // parent_id 不对
        $sql = sprintf('INSET INTO %s (name, file_id, user_id, left_id, right_id, parent_id, updated_at, created_at) SELECT name, file_id, %s, left_id + %s, right_id + %s, if(parent_id=%s,%s,parent_id) as pid, updated_at, created_at FROM %s WHERE user_id = %s AND left_id >= %s AND right_id <= %s AND deleted_at = 0',
            self::tableName(), $disk->user_id, $real_diff, $real_diff, $this->parent_id, $disk->id, self::tableName(), $this->user_id, $this->left_id, $this->right_id);
        app('db')->execute($sql);
        // 修复 parent_id
        $data = DiskModel::where('left_id', '>', $left)->where('right_id', '<', $left + $diff + 1)->select('id', 'left_id', 'right_id', 'parent_id')
            ->orderBy('left_id', 'asc')->asArray()->all();
        $parent_map = [];
        $map = [];
        foreach ($data as $item) {
            $map[$item['left_id']] = $item['id'];
            $map[$item['right_id']] = $item['id'];
            if ($item['parent_id'] == $disk->id) {
                continue;
            }
            if (isset($parent_map[$item['parent_id']])) {
                DiskModel::where('id', $item['id'])->update([
                    'parent_id' => $parent_map[$item['parent_id']]
                ]);
                continue;
            }
            $parent_id = $map[$item['left_id'] - 1];
            $parent_map[$item['parent_id']] = $parent_id;
            DiskModel::where('id', $item['id'])->update([
                'parent_id' => $parent_id
            ]);
        }
        return true;
    }

    public function deleteThis() {
        $this->where('user_id', $this->user_id)
            ->where('left_id', '>=', $this->left_id)
            ->where('right_id', '<=', $this->right_id)
            ->delete();
        $num = $this->left_id - $this->right_id - 1;
        $this->where('user_id', $this->user_id)
            ->where('right_id', '>', $this->right_id)
            ->updateIncrement('right_id', $num);
        $this->where('user_id', $this->user_id)
            ->where('left_id', '>', $this->right_id)
            ->updateIncrement('left_id', $num);
    }

    /**
     * 软删除
     * @return bool|mixed
     */
    public function softDeleteThis() {
        self::where('user_id', $this->user_id)
            ->where('left_id', '>', $this->left_id)
            ->where('right_id', '<', $this->right_id)
            ->update([
                'deleted_at' => 1
            ]);
        $this->deleted_at = time();
        return $this->save();
    }

    /**
     * 还原
     * @return mixed
     */
    public function resetThis() {
        return self::where('user_id', $this->user_id)
            ->where('left_id', '>=', $this->left_id)
            ->where('right_id', '<=', $this->right_id)
            ->update([
                'deleted_at' => 0
            ]);
    }

    /**
     * 获取子代
     * @return static[]
     */
    public function getChildren() {
        return static::where('parent_id', $this->id)->all() ;
    }

    /**
     * 获取所有后代
     * @return mixed
     */
    public function getAllChildren() {
        return static::where('left_id', '>', $this->left_id)->where('right_id', '<', $this->right_id)->all();
    }

    /**
     * 添加到下一个
     * @param integer $left 上一个的左值
     * @return bool|mixed
     * @throws \Exception
     */
    public function addByLeft($left) {
        $this->left_id = $left + 1;
        $this->right_id = $left + 2;
        self::where('user_id', $this->user_id)
            ->where('left_id', '>', $left)
            ->updateIncrement('left_id', 2);
        self::where('user_id', $this->user_id)
            ->where('right_id', '>', $left)
            ->updateIncrement('right_id', 2);
        return $this->save();
    }

    /**
     * 添加到第一个节点
     */
    public function addAsFirst() {
        $left = 0;
        if ($this->parent_id > 0) {
            $left = self::where('id', $this->parent_id)->value('left_id');
        }
        return $this->addByLeft($left);
    }

    /**
     * 添加到节点后面
     * @param DiskModel $model
     * @return bool|mixed
     * @throws \Exception
     */
    public function addAfter(DiskModel $model) {
        return $this->addByLeft($model->right_id);
    }

    /**
     * 添加到节点前面
     * @param DiskModel $model
     * @return bool|mixed
     * @throws \Exception
     */
    public function addBefore(DiskModel $model) {
        $result = $this->addByLeft($this->left_id - 1);
        if ($result) {
            $model->left_id += 2;
            $model->right_id += 2;
        }
        return $result;
    }

    /**
     * 添加到最后一个节点
     */
    public function addAsLast() {
        // 需要判断当前父节点有没有子节点
        if ($this->parent_id > 0) {
            $parent = self::find($this->parent_id);
            if ($parent->left_id == $parent->right_id - 1) {
                // 没有子代
                return $this->addByLeft($parent->left_id);
            }
            return $this->addByLeft($this->right_id - 1);
        }
        $right = intval(self::where('parent_id', $this->parent_id)->max('right_id'));
        return $this->addByLeft($right);
    }

    /**
     * 添加第一个子节点
     * @param DiskModel $model
     * @return bool|mixed
     * @throws \Exception
     */
    public function addFirstChild(DiskModel $model) {
        $result = $model->addByLeft($this->left_id);
        if ($result) {
            $this->right_id += 2;
        }
        return $result;
    }

    /**
     * 添加一个子节点到最后
     * @param DiskModel $model
     * @return bool|mixed
     * @throws \Exception
     */
    public function addLastChild(DiskModel $model) {
        $result = $model->addByLeft($this->right_id - 1);
        if ($result) {
            $this->right_id += 2;
        }
        return $result;
    }

    /**
     * 后面追加
     * @param DiskModel $model
     * @return bool|int
     * @throws \Exception
     */
    public function append(DiskModel $model) {
        return $this->addLastChild($model);
    }

    public function prepend(DiskModel $model) {
        return $this->addFirstChild($model);
    }


    public function getCount() {
        return ($this->right_id - $this->left_id - 1) / 2;
    }

    public function getDeletedAtAttribute() {
        $val = $this->getAttributeSource('deleted_at');
        return empty($val) ? '' : Time::format($val);
    }

    /**
     * 是否包含在内
     * @param DiskModel[] $files
     * @return bool
     */
    public function isIn($files) {
        foreach ((array)$files as $file) {
            if ($this->id == $file->id ||
                ($this->left_id > $file->left_id && $this->right_id < $file->right_id)) {
                return true;
            }
        }
        return false;
    }

    public function isSameName() {
        $count = static::where('id', '<>', $this->id)
            ->where('user_id', auth()->id())
            ->where('parent_id', $this->parent_id)->where('name', $this->name)->count();
        return $count > 0;
    }

    /**
     * @param $id
     * @return array|bool
     * @throws \Exception
     */
    public static function findOneByAuth($id) {
        return static::auth()->where('id', $id)->one();
    }

    /**
     * @param DiskModel[] $files
     * @param DiskModel $disk
     * @throws \Exception
     */
    public static function saveDiskTo(array $files, $disk) {
        if (empty($disk)) {
            $disk = new static([
                'id' => 0,
                'parent_id' => -1,
                'left_id' => static::where('user_id', auth()->id())->max('right_id'),
                'user_id' => auth()->id()
            ]);
        }
        foreach ($files as $file) {
            $file->copyTo($disk);
        }
    }
}