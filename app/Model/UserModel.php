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
	public function fillWeb($name ,  $email, $pwd)
	{
		$arr = array();
		$arr['email'] = $email;
		$arr['name'] = $name;
		$arr['pwd'] = $pwd;
		$arr['udate'] = $arr['cdate'] = OTime::Now();
		return $this->add($arr);
	}
	
	public function findByEmail($email)
	{
		return $this->findOne("email = '{$email}'");
	}
	
	public function findByUser($email, $pwd)
	{
		$result = $this->findOne(array("email = '{$email}'","pwd = '{$pwd}'"));
		if(is_object($result))
		{
			$result = $result->id;
		}
		return $result;
	}
	
	public function role()
	{
		return $this->hasOne('App\Model\GroupModel','group','id');
	}
}