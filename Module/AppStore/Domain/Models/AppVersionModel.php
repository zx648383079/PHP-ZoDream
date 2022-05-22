<?php
declare(strict_types=1);
namespace Module\AppStore\Domain\Models;

use Domain\Model\Model;

class AppVersionModel extends Model {

	public static function tableName() {
        return 'app_version';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,20',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'created_at' => 'Created At',
        ];
    }

}