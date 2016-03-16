<?php
namespace Domain\Model\WeChat;

use Zodream\Domain\Model;
class Message_resourcesModel extends Model {
	protected $table = 'message_resources';
	
	protected $fillable = array(
		'account_id',
		'detail',
		'type',
		'status',
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