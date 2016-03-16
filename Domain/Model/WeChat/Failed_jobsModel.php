<?php
namespace Domain\Model\WeChat;

use Zodream\Domain\Model;
class Failed_jobsModel extends Model {
	protected $table = 'failed_jobs';
	
	protected $fillable = array(
		'connection',
		'queue',
		'payload',
		'failed_at'
	);
	
	public function findPage($search, $start, $count) {
		return $this->find(array(
				'where' => " like '%{$search}%'",
				'limit' => $start.','.$count,
				'order' => ' desc' 
		));
	}
}