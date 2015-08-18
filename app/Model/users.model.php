<?php 
	/*********************************
	用户表的连接
	*********************************/
	namespace App\Model;
	
	use App\Lib\PdoSql;
	
	class Users extends PdoSql{
		public $table="users";
	}