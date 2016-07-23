<?php
namespace Domain\Model\WeChat;

use Domain\Model\Model;
/**
* Class WechatMessageModel
* @property integer $id
* @property integer $wechat_id
* @property integer $fan_id
* @property integer $create_at
* @property integer $resource_id
* @property integer $reply_id
* @property string $content
* @property string $msg_id
*/
class WechatMessageModel extends Model {
	public static $table = 'wechat_message';

	protected function rules() {
		return array (
		  'wechat_id' => 'int',
		  'fan_id' => 'int',
		  'create_at' => 'int',
		  'resource_id' => 'int',
		  'reply_id' => 'int',
		  'content' => '',
		  'msg_id' => 'string:3-25',
		);
	}

	protected function labels() {
		return array (
		  'id' => 'Id',
		  'wechat_id' => 'Wechat Id',
		  'fan_id' => 'Fan Id',
		  'create_at' => 'Create At',
		  'resource_id' => 'Resource Id',
		  'reply_id' => 'Reply Id',
		  'content' => 'Content',
		  'msg_id' => 'Msg Id',
		);
	}
}