<?php 
/*********************************
用户权限表
create table zx_roles ( 
	id int(11) not null AUTO_INCREMENT PRIMARY KEY, 
	name varchar(40)
)charset = utf8;
*********************************/
namespace App\Model;


class RolesModel extends Model{
	protected $table = "roles";
	
	protected $fillable = array(
		'id',
		'name'
	);
}