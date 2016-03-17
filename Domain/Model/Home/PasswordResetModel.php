<?php
namespace Domain\Model\Home;

use Zodream\Domain\Model;
class PasswordResetModel extends Model {
	protected $table = 'password_reset';
	
	protected $fillable = array(
		'token',
		'email',
		'create_at'
	);

	public function findByToken($token) {
		return $this->findOne("token = '{$token}'");
	}
}