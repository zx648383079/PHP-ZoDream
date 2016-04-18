<?php
namespace Domain\Model\WeChat;

use Domain\Model\Model;
use Zodream\Infrastructure\ObjectExpand\StringExpand;

class WeChatModel extends Model {
	protected $table = 'wechat';
	
	protected $fillAble = array(
		'name',
		'original_id',
		'app_id',
		'app_secret',
		'token',
		'aes_key',
		'account',
		'tag',
		'access_token',
		'type',
		'status'
	);

	public function fill() {
		$args = func_get_arg(0);
		$args['tag'] = StringExpand::random(10);
		return parent::fill($args);
	}
}