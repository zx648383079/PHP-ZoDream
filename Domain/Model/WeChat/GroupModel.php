<?php
namespace Domain\Model\WeChat;

use Domain\Model\Model;
class GroupModel extends Model {
	protected $table = 'wechat_group';
	
	protected $fillAble = array(
		'wechat_id',
		'parent',
		'name',
		'fan_count',
		'is_default',
		'update_at',
		'create_at'
	);
}