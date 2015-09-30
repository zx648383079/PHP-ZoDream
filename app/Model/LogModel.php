<?php 
/*********************************
访问纪录
*********************************/
namespace App\Model;


class LogModel extends Model{
	protected $table = "logs";
	
	protected $fillable = array(
		'name',
		'data',
		'ip',
		'url',
		'cdate'
	);
}