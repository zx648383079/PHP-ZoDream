<?php 
namespace App\Model;
/*
系统表
create table zx_system ( 
	id int(11) not null AUTO_INCREMENT PRIMARY KEY, 
	page varchar(20) not null UNIQUE,
	content text,
	show bool,
	user_id int(11),
	udate int(11),
	cdate int(11) 
)charset = utf8;
*/

class SystemModel extends Model
{
	protected $table = "system";
	
	protected $fillable = array(
		'page',
		'content',
		'show',
		'user_id',
		'udate',
		'cdate'
	);
	
	public function findByPage($page) 
	{
		return $this->findOne(array(
			"page = '$page'",
			'`show` = 1'
		));
	}
	
	public function fillOrUpdate($data) 
	{
		if(array_key_exists('content', $data)) 
		{
			$data['content'] = addslashes($data['content']);
		}
		
		if(array_key_exists('id', $data) && $data['id'] > 0) 
		{
			return $this->updateById($data, $data['id']);
		}
		return $this->fill($data);
	}
}