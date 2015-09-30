<?php 
namespace App\Model;
/*
create table zx_kind ( 
	id int(3) not null AUTO_INCREMENT PRIMARY KEY, 
	name varchar(20) not null UNIQUE, 
	user_id int(11), 
	udate int(11), 
	cdate int(11) 
)charset = utf8
*/


class KindModel extends Model{
	protected $table = "kind";
	
	protected $fillable = array(
		'name',
		'user_id',
		'udate',
		'cdate'
	);
}