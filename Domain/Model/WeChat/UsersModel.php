<?php
namespace Domain\Model\WeChat;

use Zodream\Domain\Model;
class UsersModel extends Model {
	protected $table = 'users';
	
	protected $fillable = array(
		'name',
		'email',
		'password',
		'is_admin',
		'remember_token',
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