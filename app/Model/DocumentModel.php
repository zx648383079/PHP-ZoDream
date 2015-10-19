<?php 
namespace App\Model;
/*
系统表
create table zx_document ( 
	id int(11) not null AUTO_INCREMENT PRIMARY KEY, 
	pid int(11),
	num int(5),
	title varchar(50) not null UNIQUE,
	content text,
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
		'num',
		'title',
		'content',
		'user_id',
		'udate',
		'cdate'
	);
	
	public function findTitle() 
	{
		return $this->findByHelper(array(
			'select' => 'id,pid,title',
			'from' => $this->table,
			'order' => 'pid,num'
		));
	}
	
	public function deleteAllById($id)
	{
		return $this->delete("id = {$id} OR pid = {$id}");
	}
	
	public function fillOrUpdate($data) 
	{
		if(array_key_exists('id', $data) && $data['id'] > 0) 
		{
			unset($data['num']);
			unset($data['pid']);
			return $this->updateById($data, $data['id']);
		}
		
		if(!array_key_exists('pid', $data)) 
		{
			$data['pid'] = 0;
		}
		
		$data['num'] = $this->count('pid = '.$data['pid']);
		
		return $this->fill($data);
	}
	
	public function moveParent($data)
	{
		$num = $this->count('pid = '.$data['pid']);
		return $this->updateById(array( 'pid' => $data['pid'], 'num' => $num ), $data['id'] );
	}
	
	public function move( $data )
	{
		$model = $this->findById($data['id']);
		$pid = $model->pid;
		$num = $model->num;
		switch ($data['num']) {
			case '1':
				$this->updateOne('num', "pid = {$pid} AND num = ".($num - 1), 1 );
				$this->updateOne('num', "id = {$data['id']}", -1);
				break;
			case '2':
				$this->updateOne('num', "pid = {$pid} AND num = ".($num + 1), -1);
				$this->updateOne('num', "id = {$data['id']}");
				break;
			default:
				break;
		}
	}
}