<?php 
namespace App\Model;

/*********************************
访问纪录
*********************************/

class LogModel extends Model
{
	protected $table = "logs";
	
	protected $fillable = array(
		'ip',
		'url',
		'cdate'
		);
}