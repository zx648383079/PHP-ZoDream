<?php
namespace Domain\Model\WeChat;

use Zodream\Domain\Model;
class Fan_groupsModel extends Model {
	protected $table = 'fan_groups';
	
	protected $fillable = array(
		'account_id',
		'group_id',
		'title',
		'fan_count',
		'is_default',
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