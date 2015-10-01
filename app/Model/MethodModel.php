<?php 
namespace App\Model;
/***
create table zx_method ( 
	id int(11) not null AUTO_INCREMENT PRIMARY KEY, 
	title varchar(255),
	`keys` varchar(255),
	kind int(5),
	content text,
	user_id int(11) not null,
	udate int(11),
	cdate int(11) 
)charset = utf8;
*/

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
	
	public function deleteById($id) 
	{
		return $this->delete("id = {$id}");
	}
}