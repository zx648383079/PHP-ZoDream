<?php
namespace Domain\Model\Home;

use Domain\Model\Model;
/**
* Class FriendLinkModel
* @property integer $id
* @property integer $position
* @property string $name
* @property string $url
* @property string $description
* @property string $logo
* @property integer $type
*/
class FriendLinkModel extends Model {
	public static function tableName() {
        return 'friend_link';
    }

    protected function rules() {
		return array (
		  'position' => 'int',
		  'name' => 'string:3-100',
		  'url' => 'string:3-255',
		  'description' => '',
		  'logo' => 'string:3-255',
		  'type' => 'int',
		);
	}

	protected function labels() {
		return array (
		  'id' => 'Id',
		  'position' => 'Position',
		  'name' => 'Name',
		  'url' => 'Url',
		  'description' => 'Description',
		  'logo' => 'Logo',
		  'type' => 'Type',
		);
	}
}