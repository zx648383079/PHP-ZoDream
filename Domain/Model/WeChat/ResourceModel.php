<?php
namespace Domain\Model\WeChat;

use Domain\Model\Model;
class ResourceModel extends Model {
	protected $table = 'wechat_resource';
	
	protected $fillAble = array(
		'id',
		'wechat_id',
		'detail',
		'type',
		'status',
		'update_at',
		'create_at'
	);
}