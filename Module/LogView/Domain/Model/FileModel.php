<?php
namespace Module\LogView\Domain\Model;

use Domain\Model\Model;

/**
 * Class FileModel
 * @package Module\LogView\Domain\Model
 * @property integer $id
 * @property string $name
 * @property string $md5
 * @property integer $created_at
 * @property integer $updated_at
 */
class FileModel extends Model {

    public static function tableName() {
        return 'log_file';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,255',
            'md5' => 'required|string:0,32',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'md5' => 'Md5',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

}