<?php 
	/*********************************
	用户权限表
	*********************************/
	namespace App\Model;
	
	
	class GroupModel extends Model{
		protected $table = "groups";
		
		protected $fillable = array(
			'id',
			'name'
		);
	}