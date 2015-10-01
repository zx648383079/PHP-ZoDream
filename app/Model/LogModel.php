<?php 
/*********************************
访问纪录
create table zx_logs ( 
	id int(11) not null AUTO_INCREMENT PRIMARY KEY, 
	name varchar(30),
	data varchar(255),
	ip varchar(20),
	url varchar(255),
	cdate int(11) 
)charset = utf8;
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