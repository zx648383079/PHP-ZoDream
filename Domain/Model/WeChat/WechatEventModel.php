<?php
namespace Domain\Model\WeChat;

use Domain\Model\Model;
/**
* Class WechatEventModel
* @property integer $id
* @property integer $wechat_id
* @property string $key
* @property string $type
* @property string $value
* @property integer $create_at
*/
class WechatEventModel extends Model {
	public static $table = 'wechat_event';

	protected function rules() {
		return array (
		  'wechat_id' => 'int',
		  'key' => 'string:3-128',
		  'type' => '',
		  'value' => 'string:3-30',
		  'create_at' => 'int',
		);
	}

	protected function labels() {
		return array (
		  'id' => 'Id',
		  'wechat_id' => 'Wechat Id',
		  'key' => 'Key',
		  'type' => 'Type',
		  'value' => 'Value',
		  'create_at' => 'Create At',
		);
	}
}