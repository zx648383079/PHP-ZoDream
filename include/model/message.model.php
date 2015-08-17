<?php 
	/*********************************
	用户表的连接
	*********************************/
	namespace Model;
	
	use \PdoSQL;
	
	class Message extends PdoSQL{
		public $table="message";
	}