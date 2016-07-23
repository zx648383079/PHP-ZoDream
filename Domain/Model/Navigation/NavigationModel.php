<?php
namespace Domain\Model\Navigation;

use Domain\Model\Model;
/**
* Class NavigationModel
* @property integer $id
* @property string $name
* @property string $url
* @property integer $category_id
* @property integer $position
* @property integer $user_id
*/
class NavigationModel extends Model {
	public static $table = 'navigation';

	protected function rules() {
		return array (
		  'name' => 'required|string:3-100',
		  'url' => 'required|string:3-255',
		  'category_id' => 'required|int',
		  'position' => 'int',
		  'user_id' => 'required|int',
		);
	}

	protected function labels() {
		return array (
		  'id' => 'Id',
		  'name' => 'Name',
		  'url' => 'Url',
		  'category_id' => 'Category Id',
		  'position' => 'Position',
		  'user_id' => 'User Id',
		);
	}
}