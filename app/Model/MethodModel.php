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
	
	public function findByKey( $key , $kind = null)
	{
		$where = array(
			"(m.title like '%$key%'",
			'or' => "m.keys like '%$key%')"
		);
		if( !empty($kind) ) 
		{
			$where[] = "m.kind = $kind";
		}
		
		$sql = array(
			'select' => 'm.id as id,m.title as title,m.keys as `keys`,m.content as content,k.name as kind,m.cdate as cdate,m.udate as udate',
			'from' => "{$this->table} m",
			'left' => array(
				'kind k',
				'm.kind = k.id'
			),
			'where' => $where,
			'order' => 'm.udate desc'
		);
		
		return $this->findByHelper($sql);
	}
}