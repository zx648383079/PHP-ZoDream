<?php
namespace Module\Disk\Domain\Model;

use Domain\Model\Model;

/**
 * Class ShareUserModel
 * @package Domain\Model\Disk
 * @property integer $id
 * @property integer $share_id
 * @property integer $disk_id
 */
class ShareFileModel extends Model {

    public static function tableName(): string {
        return 'disk_share_file';
    }

    protected function rules(): array {
        return [
            'disk_id' => 'required|int',
            'share_id' => 'required|int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'disk_id' => 'Disk Id',
            'share_id' => 'Share Id',
        ];
    }
}