<?php
namespace Domain\Model\WeChat;

use Zodream\Domain\Model;
class Password_resetsModel extends Model {
	protected $table = 'password_resets';
	
	protected $fillable = array(
		'email',
		'token',
		'created_at'
	);
	
	public function findPage($search, $start, $count) {
		return $this->find(array(
				'where' => " like '%{$search}%'",
				'limit' => $start.','.$count,
				'order' => ' desc' 
		));
	}
}