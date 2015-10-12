<?php 
/*********************************
用户表的连接
create table zx_users ( 
	id int(11) not null AUTO_INCREMENT PRIMARY KEY, 
	email varchar(100) not null UNIQUE,
	name varchar(20) not null UNIQUE,
	pwd varchar(32),
	token varchar(32),
	udate int(11),
	cdate int(11) 
)charset = utf8;
*********************************/
namespace App\Model;

use App\Lib\Object\OTime;

class UserModel extends Model{
	protected $table = "users";
	
	
	protected $fillable = array(
		'email',
		'name',
		'pwd',
		'token',
		'udate',
		'cdate'
	);
	
	public function findByEmail($email)
	{
		return $this->findOne(array("email = '{$email}'"));
	}
	
	public function findByToken($token)
	{
		$result = $this->findOne(array("token = '{$token}'"));
		if(is_object($result))
		{
			$result = $result->id;
		}
		return $result;
	}
	
	public function setToken($id)
	{
		if(func_num_args() < 2)
		{
			$token = md5(microtime().$id);			
		}else {
			$token = func_get_arg(1);
		}
		$this->update(array('token' => $token),array("id = {$id}"));
		return $token;
	}
	
	public function findByUser($data)
	{
		$result = $this->findOne(array("email = '{$data['email']}'","pwd = '{$data['pwd']}'"));
		if(is_object($result))
		{
			$result = $result->id;
		}
		return $result;
	}
	
	public function findWithRoles( $where, $field )
	{
		$sql = array(
			'select' => $field,
			'from' => "{$this->table} u",
			'left' => array(
				'groups g',
				'u.group = g.id'
			),
			'where' => $where
		);
		
		return $this->findByHelper($sql);
	}
	
	public function role()
	{
		return $this->hasOne('App\Model\GroupModel','group','id');
	}
}