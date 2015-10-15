<?php 
namespace App\Model;
/*
系统表
create table zx_document ( 
	id int(11) not null AUTO_INCREMENT PRIMARY KEY, 
	pid int(11),
	title varchar(50) not null UNIQUE,
	content text,
	url varchar(50) not null,
	user_id int(11),
	udate int(11),
	cdate int(11) 
)charset = utf8;
*/

class DocumentModel extends Model
{
	protected $table = "document";
	
	protected $fillable = array(
		'pid',
		'title',
		'content',
		'url',
		'user_id',
		'udate',
		'cdate'
	);
	
	public function findByPage($page) 
	{
		return $this->findOne('page = '.$page);
	}
	
	public function findTitle() 
	{
		
	}
}