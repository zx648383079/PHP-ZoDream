<?php
namespace Domain\Model\WeChat;

use Domain\Model\Model;
class ReplyModel extends Model {
	protected $table = 'wechat_reply';
	
	protected $fillAble = array(
		'wechat_id',
		'type',
		'name',
		'trigger_keyword',
		'trigger_type',
		'content',
		'group_ids',
		'update_at',
		'create_at'
	);
}