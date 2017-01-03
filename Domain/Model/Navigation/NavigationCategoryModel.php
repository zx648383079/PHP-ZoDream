<?php
namespace Domain\Model\Navigation;

use Domain\Model\Model;
/**
* Class NavigationCategoryModel
* @property integer $id
* @property string $name
* @property integer $position
* @property integer $user_id
*/
class NavigationCategoryModel extends Model {
	public static function tableName() {
        return 'navigation_category';
    }

    protected $primaryKey = array (
	  'id',
	  'name',
	);

	protected function rules() {
		return array (
		  'name' => 'required|string:3-20',
		  'position' => 'int',
		  'user_id' => 'required|int',
		);
	}

	protected function labels() {
		return array (
		  'id' => 'Id',
		  'name' => 'Name',
		  'position' => 'Position',
		  'user_id' => 'User Id',
		);
	}
}