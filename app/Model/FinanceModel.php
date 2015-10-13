<?php 
namespace App\Model;

use App\Lib\Auth;
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
	
	public function findByKind( $kind = null , $start = 0 , $max = 20)
	{
		$sql = array(
			'select' => 'count(*) as total',
			'from' => "{$this->table}",
			'where' => array(
				'user_id' => Auth::user()->id
			),
			'order' => 'happen desc,udate desc'
		);
		if( $kind !== null) 
		{
			$sql['where'][] = "kind = $kind";
		}
		
		$data = $this->findByHelper($sql);	
		if(! empty($data))
		{
			$data = $data[0];
			$data['max'] = $max;
			$data['index'] = $start = $start > $data['total'] ? $data['total'] : $start;
			$sql['select'] = '*';	
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