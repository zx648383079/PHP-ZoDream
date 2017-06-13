<?php
namespace Module\Blog\Domain\Model;

use Domain\Model\Model;
/**
 * Class TermModel
 * @property integer $id
 * @property string $name
 * @property string $keywords
 * @property string $description
 * @property integer $create_at
 */
class TermModel extends Model {
	public static function tableName() {
        return 'term';
    }

	protected function rules() {
		return [
            'name' => 'required|string:3-200',
            'keywords' => 'string:3-200',
            'description' => 'string:3-200',
            'create_at' => 'int',
        ];
	}

	protected function labels() {
		return [
            'id' => 'Id',
            'name' => 'Name',
            'description' => 'description',
            'keywords' => 'keywords',
            'create_at' => 'create_at'
        ];
	}

}