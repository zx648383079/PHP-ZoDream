<?php
namespace Domain\Model\WeChat;

use Zodream\Domain\Model;
class EventsModel extends Model {
	protected $table = 'events';
	
	protected $fillable = array(
		'account_id',
		'key',
		'type',
		'value',
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