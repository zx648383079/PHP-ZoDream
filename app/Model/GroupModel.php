<?php 
	/*********************************
	用户权限表
	*********************************/
	namespace App\Model;
	
	
	class GroupModel extends Model{
		protected $table = "group";
		
		protected $fillable = array(
			'id',
			'name'
		);
	}