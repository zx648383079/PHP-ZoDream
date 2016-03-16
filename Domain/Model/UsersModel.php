<?php
namespace Domain\Model;

use Zodream\Domain\Model;
class UsersModel extends Model {
	protected $table = 'users';
	
	protected $fillable = array(
		'username',
		'email',
		'password',
		'cdate'
	);
	
	public function findPage($start) {
		return $this->find(array(
				'limit' => ($start * 20).',20',
				'order' => 'cdate desc' 
		), 'id,username,email,cdate');
	}
}