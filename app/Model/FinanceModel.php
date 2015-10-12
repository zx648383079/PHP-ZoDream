<?php 
namespace App\Model;
/***
create table zx_finance ( 
	id int(11) not null AUTO_INCREMENT PRIMARY KEY, 
	kind boolean,
	money real,
	mark varchar(255),
	happen datetime,
	user_id int(11) not null,
	udate int(11),
	cdate int(11) 
)charset = utf8;
*/

class FinanceModel extends Model{
	protected $table = "finance";
	
	protected $fillable = array(
		'kind',
		'money',
		'mark',
		'happen',
		'user_id',
		'udate',
		'cdate'
	);
	
	public function deleteById($id) 
	{
		return $this->delete("id = {$id}");
	}
	
	public function findByKind( $key , $kind = null , $start = 0 , $max = 20)
	{
		$where = array(
			"(title like '%$key%'",
			'or' => "m.keys like '%$key%')"
		);
		if( !empty($kind) ) 
		{
			$where[] = "m.kind = $kind";
		}
		
		$sql = array(
			'select' => 'count(*) as total',
			'from' => "{$this->table} m",
			'left' => array(
				'kind k',
				'm.kind = k.id'
			),
			'where' => $where,
			'order' => 'm.udate desc'
		);
		$data = $this->findByHelper($sql);	
		if(! empty($data))
		{
			$data = $data[0];
			$data['max'] = $max;
			$data['index'] = $start = $start > $data['total'] ? $data['total'] : $start;
			$sql['select'] = 'm.id as id,m.title as title,m.keys as `keys`,m.content as content,k.name as kind,m.cdate as cdate,m.udate as udate';	
			$sql['limit'] = $start.','.$max;
			$data['data'] = $this->findByHelper($sql);
		}else{
			$data = array(
				'total' => 0,
				'max' => $max,
				'index' => 0,
				'data' => ''
			);
		}
		return $data;
	}
}