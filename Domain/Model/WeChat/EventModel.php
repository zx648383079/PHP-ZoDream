<?php
namespace Domain\Model\WeChat;

use Domain\Model\Model;
class EventModel extends Model {
	protected $table = 'wechat_event';
	
	protected $fillAble = array(
		'wechat_id',
		'key',
		'type',
		'value',
		'create_at'
	);
}