<?php
namespace Domain\Model\WeChat;

use Zodream\Domain\Model;
class AccountsModel extends Model {
	protected $table = 'accounts';
	
	protected $fillable = array(
		'name',
		'original_id',
		'app_id',
		'app_secret',
		'token',
		'aes_key',
		'wechat_account',
		'tag',
		'access_token',
		'account_type',
		'sync_status',
		'created_at',
		'updated_at',
		'deleted_at'
	);
	
	public function findPage($search, $start, $count) {
		return $this->find(array(
				'where' => " like '%{$search}%'",
				'limit' => $start.','.$count,
				'order' => ' desc' 
		));
	}
}