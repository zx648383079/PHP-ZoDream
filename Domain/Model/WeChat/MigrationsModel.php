<?php
namespace Domain\Model\WeChat;

use Zodream\Domain\Model;
class MigrationsModel extends Model {
	protected $table = 'migrations';
	
	protected $fillable = array(
		'migration',
		'batch'
	);
	
	public function findPage($search, $start, $count) {
		return $this->find(array(
				'where' => " like '%{$search}%'",
				'limit' => $start.','.$count,
				'order' => ' desc' 
		));
	}
}