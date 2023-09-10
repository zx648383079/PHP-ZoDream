<?php
namespace Module\Disk\Domain\Model;

use Module\Auth\Domain\Model\UserModel;
use Domain\Model\Model;
use Module\Auth\Domain\Model\UserSimpleModel;


/**
 * Class ShareModel
 * @package Domain\Model\Disk
 * @property integer $id
 * @property string $name
 * @property integer $mode 分享模式
 * @property string $password
 * @property integer $user_id
 * @property integer $death_at 过期时间
 * @property integer $view_count 查看人数
 * @property integer $down_count 下载人数
 * @property integer $save_count 保存人数
 * @property integer $created_at
 * @property integer $updated_at
 */
class ShareModel extends Model {

    const SHARE_PUBLIC = 0; //公开分享
    const SHARE_PROTECTED = 1; //密码分享
    const SHARE_PRIVATE = 2;  //分享给个人

    public static function tableName(): string {
        return 'disk_share';
    }

    protected function rules(): array {
        return [
            'name' => 'required|string:0,100',
            'mode' => 'int:0,99',
            'password' => 'string:0,20',
            'user_id' => 'int',
            'death_at' => 'int',
            'view_count' => 'int',
            'save_count' => 'int',
            'down_count' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'mode' => 'Mode',
            'password' => 'Password',
            'user_id' => 'User Id',
            'death_at' => 'Death At',
            'view_count' => 'View Count',
            'save_count' => 'Save Count',
            'down_count' => 'Down Count',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function scopeAuth($query) {
        return $query->where('user_id', auth()->id());
    }

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

    public function getFile($id = null) {
        $shareFiles = DiskModel::whereIn('id', ShareFileModel::where('share_id', $this->id)->pluck('disk_id'))->all();
        if (empty($id)) {
            return $shareFiles;
        }
        $files = DiskModel::whereIn('id', (array)$id)->all();
        return array_filter($files, function (DiskModel $file) use ($shareFiles) {
            return $file->isIn($shareFiles);
        });
    }
}