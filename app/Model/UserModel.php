<?php 
	/*********************************
	用户表的连接
	*********************************/
	namespace App\Model;
	
	use App\Main;
	use App\Lib\TimeDeal;
	
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
			$arr['udate'] = $arr['cdate'] = TimeDeal::Now();
			return $this->add($arr);
		}
		
		public function findByEmail($email)
		{
			return $this->isOne("email = '{$email}'");
		}
		
		public function findByUser($email, $pwd)
		{
			return $this->isOne(array("email = '{$email}'","pwd = '{$pwd}'"));
		}
		
		public function role()
		{
			return $this->hasOne('App\Model\GroupModel','group','id');
		}
	}