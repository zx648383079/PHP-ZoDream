<?php
declare(strict_types=1);
namespace Module\AppStore\Domain\Models;

use Domain\Model\Model;

class AppFileModel extends Model {

	public static function tableName() {
        return 'app_file';
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