<?php 
	/*********************************
	用户表的连接
	*********************************/
	namespace App\Model;
	
	use App\Lib\PdoSql;
	
	class MessageModel extends PdoSql{
		public $table="message";
	}