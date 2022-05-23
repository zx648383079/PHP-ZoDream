<?php
declare(strict_types=1);
namespace Module\AppStore\Domain\Models;

use Domain\Model\Model;

/**
 *
 * @property integer $id
 * @property integer $app_id
 * @property integer $version_id
 * @property string $os
 * @property string $framework
 * @property integer $url_type
 * @property string $url
 * @property integer $size
 * @property integer $view_count
 * @property integer $updated_at
 * @property integer $created_at
 */
class AppFileModel extends Model {

	public static function tableName() {
        return 'app_file';
    }

    protected function rules() {
        return [
            'app_id' => 'required|int',
            'version_id' => 'required|int',
            'os' => 'string:0,20',
            'framework' => 'string:0,10',
            'url_type' => 'int:0,127',
            'url' => 'string:0,255',
            'size' => 'int',
            'view_count' => 'int',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'app_id' => 'App Id',
            'version_id' => 'Version Id',
            'os' => 'Os',
            'framework' => 'Framework',
            'url_type' => 'Url Type',
            'url' => 'Url',
            'view_count' => 'View Count',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

}