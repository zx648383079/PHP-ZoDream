<?php 
	/*********************************
	用户表的连接
	*********************************/
	namespace App\Model;
	
	
	class WechatModel extends Model{
		protected $table = "wechat";
		
		protected $fillable = array(
			'type',
			'keyword',
			'content',
			'user_id',
			'msg',
			'cdate'
		);
	}