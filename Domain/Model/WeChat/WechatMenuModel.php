<?php
namespace Domain\Model\WeChat;

use Domain\Model\Model;
/**
* Class WechatMenuModel
* @property integer $id
* @property integer $wechat_id
* @property integer $parent
* @property string $name
* @property string $type
* @property string $key
* @property integer $position
* @property integer $update_at
* @property integer $create_at
*/
class WechatMenuModel extends Model {
	public static $table = 'wechat_menu';

	protected function rules() {
		return array (
		  'wechat_id' => 'int',
		  'parent' => 'int',
		  'name' => 'required|string:3-45',
		  'type' => '',
		  'key' => 'string:3-200',
		  'position' => 'int',
		  'update_at' => 'int',
		  'create_at' => 'int',
		);
	}

	protected function labels() {
		return array (
		  'id' => 'Id',
		  'wechat_id' => 'Wechat Id',
		  'parent' => 'Parent',
		  'name' => 'Name',
		  'type' => 'Type',
		  'key' => 'Key',
		  'position' => 'Position',
		  'update_at' => 'Update At',
		  'create_at' => 'Create At',
		);
	}
}