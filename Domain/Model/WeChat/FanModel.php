<?php
namespace Domain\Model\WeChat;

use Domain\Model\Model;
class FanModel extends Model {
	protected $table = 'wechat_fan';
	
	protected $fillAble = array(
		'wechat_id',
		'group_id',
		'openid',
		'nickname',
		'signature',
		'remark',
		'sex',
		'language',
		'city',
		'province',
		'country',
		'avatar',
		'unionid',
		'liveness',
		'subscribed_at',
		'update_at',
		'create_at',
		'deleted_at'
	);
}