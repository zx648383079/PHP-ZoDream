<?php
namespace Domain\Model\WeChat;

use Domain\Model\Model;
class MenuModel extends Model {
	protected $table = 'wechat_menu';
	
	protected $fillAble = array(
		'wechat_id',
		'parent',
		'name',
		'type',
		'key',
		'position',
		'update_at',
		'create_at'
	);
}