<?php
namespace Domain\Model\Home;

use Zodream\Domain\Authentication\Auth;
use Zodream\Domain\Model;
use Zodream\Domain\Html\Page;
class UsersModel extends Model {
	protected $table = 'users';
	
	protected $fillable = array(
		'name',
		'email',
		'password',
		'remember_token',
		'role',
		'create_at'
	);
	
	public function findPage() {
		$role = Auth::user()['role'];
		$page = new Page($this->count(array(
			'role < '.$role
		)));
		$page->setPage($this->find(array(
				'where' => array(
					'role < '.$role
				),
				'order' => 'create_at desc',
				'limit' => $page->getLimit()
			), 'id,name,email,role,create_at'
		));
		return $page;
	}

	public function findByEmail($email) {
		return $this->findOne("email = '{$email}'");
	}

	public function findByToken($token) {
		return $this->findOne("remember_token = '{$token}'");
	}
}