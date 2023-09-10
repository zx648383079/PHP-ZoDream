<?php
declare(strict_types=1);
namespace Module\ResourceStore\Domain\Models;

use Domain\Model\Model;

/**
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $res_id
 * @property integer $file_type
 * @property string $file
 * @property integer $click_count
 * @property integer $updated_at
 * @property integer $created_at
 */
class ResourceFileModel extends Model {
    public static function tableName(): string {
        return 'res_resource_file';
    }

    protected function rules(): array {
        return [
            'user_id' => 'required|int',
            'res_id' => 'required|int',
            'file_type' => 'int',
            'file' => 'required|string:0,255',
            'click_count' => 'int',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'res_id' => 'Res Id',
            'file_type' => 'File Type',
            'file' => 'File',
            'click_count' => 'Click Count',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}