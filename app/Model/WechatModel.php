<?php
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