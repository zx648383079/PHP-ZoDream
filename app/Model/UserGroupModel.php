<?php 
	/*********************************
	用户权限表
	*********************************/
	namespace App\Model;
	
	
	class UserGroupModel extends Model{
		protected $table = "user_group";
		
		protected $fillable = array(
			'id',
			'name'
		);
	}