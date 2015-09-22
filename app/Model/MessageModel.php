<?php 
/*********************************
用户表的连接
*********************************/
namespace App\Model;


class MessageModel extends Model{
	protected $table = "message";
	
	protected $fillable = array(
		'type',
		'content',
		'user_id',
		'cdate'
		);
	
}