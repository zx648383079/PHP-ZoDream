<?php 
	/*********************************
	用户表的连接
	*********************************/
	namespace App\Model;
	
	
	class UserModel extends Model{
		protected $table = "users";
		
		protected $fillable = array(
			'openid',
			'username',
			'password',
			'role_id'
		);
	}