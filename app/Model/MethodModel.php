<?php 
namespace App\Model;

class MethodModel extends Model{
	protected $table = "method";
	
	protected $fillable = array(
		'title',
		'keys',
		'kind',
		'content',
		'user_id',
		'udate',
		'cdate'
	);
}