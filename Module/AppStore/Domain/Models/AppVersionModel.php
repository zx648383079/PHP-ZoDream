<?php
declare(strict_types=1);
namespace Module\AppStore\Domain\Models;

use Domain\Model\Model;

/**
 *
 * @property integer $id
 * @property integer $app_id
 * @property string $name
 * @property string $description
 * @property integer $created_at
 */
class AppVersionModel extends Model {

	public static function tableName() {
        return 'app_version';
    }

    protected function rules() {
        return [
            'app_id' => 'required|int',
            'name' => 'required|string:0,20',
            'description' => 'string:0,255',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'app_id' => 'App Id',
            'name' => 'Name',
            'description' => 'Description',
            'created_at' => 'Created At',
        ];
    }
}