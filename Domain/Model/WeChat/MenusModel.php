<?php
namespace Domain\Model\WeChat;

use Zodream\Domain\Model;
class MenusModel extends Model {
	protected $table = 'menus';
	
	protected $fillable = array(
		'account_id',
		'parent_id',
		'name',
		'type',
		'key',
		'sort',
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