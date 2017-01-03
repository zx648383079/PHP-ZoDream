<?php
namespace Domain\Model\Blog;

use Domain\Model\Model;
/**
* Class TermModel
* @property integer $id
* @property string $name
* @property string $slug
* @property integer $group
*/
class TermModel extends Model {
	public static function tableName() {
        return 'term';
    }

    protected function rules() {
		return array (
		  'name' => 'required|string:3-200',
		  'slug' => 'string:3-200',
		  'group' => 'int',
		);
	}

	protected function labels() {
		return array (
		  'id' => 'Id',
		  'name' => 'Name',
		  'slug' => 'Slug',
		  'group' => 'Group',
		);
	}
}