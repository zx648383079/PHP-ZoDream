<?php
declare(strict_types=1);
namespace Module\AppStore\Domain\Models;

use Domain\Model\Model;

class CategoryModel extends Model {

	public static function tableName() {
        return 'app_category';
    }

	protected function rules() {
		return [
            'content' => 'string:0,255',
            'user_id' => 'required|int',
            'created_at' => 'int',
        ];
	}

	protected function labels() {
		return [
            'id' => 'Id',
            'content' => 'Content',
            'user_id' => 'User Id',
            'created_at' => 'Created At',
        ];
	}

}