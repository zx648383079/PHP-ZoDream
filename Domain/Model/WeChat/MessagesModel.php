<?php
namespace Domain\Model\WeChat;

use Zodream\Domain\Model;
class MessagesModel extends Model {
	protected $table = 'messages';
	
	protected $fillable = array(
		'account_id',
		'fans_id',
		'sent_at',
		'resource_id',
		'reply_id',
		'content',
		'msg_id',
		'created_at',
		'updated_at'
	);
	
	public function findPage($search, $start, $count) {
		return $this->find(array(
				'where' => " like '%{$search}%'",
				'limit' => $start.','.$count,
				'order' => ' desc' 
		));
	}
}