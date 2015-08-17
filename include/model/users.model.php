<?php 
	/*********************************
	用户表的连接
	*********************************/
	namespace Model;
	
	use \PdoSQL;
	
	class Users extends PdoSQL{
		public $table="users";
	}