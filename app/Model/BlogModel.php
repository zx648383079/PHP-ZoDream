<?php 
namespace App\Model;

class BlogModel extends Model{
	protected $table = "blog";
	
	protected $fillable = array(
		'pid',
		'title',
		'content',
		'user_id',
		'udate',
		'cdate'
		);
	
}