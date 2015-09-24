<?php 
/*********************************
用户表的连接
*********************************/
namespace App\Model;

use App\App;
use App\Lib\Object\OTime;

class UserModel extends Model{
	protected $table = "users";
	
	
	protected $fillable = array(
		'openid',
		'email',
		'name',
		'pwd',
		'udate',
		'cdate'
	);
	/******
	从网页注册
	*/
	public function fillWeb( $data )
	{
		$data['udate'] = $data['cdate'] = OTime::Now();
		return $this->add($data);
	}
	
	public function findByEmail($email)
	{
		return $this->findOne("email = '{$email}'");
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