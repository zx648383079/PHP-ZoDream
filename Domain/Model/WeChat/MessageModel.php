<?php
namespace Domain\Model\WeChat;

use Domain\Model\Model;
class MessageModel extends Model {
	protected $table = 'wechat_message';
	
	protected $fillAble = array(
		'wechat_id',
		'fan_id',
		'create_at',
		'resource_id',
		'reply_id',
		'content',
		'msg_id'
	);
}