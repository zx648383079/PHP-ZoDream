<?php
namespace Module\Shop\Domain\Model;

use Domain\Model\Model;
/**
* Class RegionModel
* @property integer $id
* @property string $name
* @property integer $parent_id
* @property integer $type
*/
class RegionModel extends Model {
	public static function tableName() {
        return 'shop_region';
    }

    protected function rules() {
		return array (
		  'name' => 'required|string:1-40',
		  'parent_id' => 'int',
		  'type' => 'int'
		);
	}

	protected function labels() {
		return array (
            'name' => '名称',
            'parent_id' => '上级',
            'type' => '层级'
		);
	}

    /**
     * @return Tree
     */
	public static function tree() {
        return new Tree(static::findAll());
    }
}