<?php
namespace Module\Disk\Domain\Model;

use Module\Auth\Domain\Model\UserModel;
use Domain\Model\Model;
use Module\Auth\Domain\Model\UserSimpleModel;

/**
 *
 * @property integer $id
 * @property string $name
 * @property string $extension
 * @property string $md5
 * @property string $location
 * @property integer $size
 * @property integer $updated_at
 * @property integer $created_at
 */
class ClientFileModel extends Model {

    public static function tableName(): string {
        return 'disk_client_file';
    }

    protected function rules(): array {
        return [
            'name' => 'required|string:0,100',
            'extension' => 'required|string:0,20',
            'md5' => 'required|string:0,32',
            'location' => 'required|string:0,200',
            'size' => 'int',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'extension' => 'Extension',
            'md5' => 'Md5',
            'location' => 'Location',
            'size' => 'Size',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}