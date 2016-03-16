<?php
namespace Domain\Model;

use Zodream\Domain\Model;
class MessagesModel extends Model {
	protected $table = 'messages';
	
	protected $fillable = array(
		'id',
		'name',
		'email',
		'title',
		'content',
		'ip',
		'readed',
		'cdate'
	);
	
	public function findPage($start) {
		return $this->find(array(
				'limit' => ($start * 20).',20',
				'order' => 'cdate desc' 
		), 'id,name,email,title,ip,readed,cdate');
	}
	
	public function findById($id, $field = null) {
		$data = parent::findById($id);
		if ($data['readed'] == 0) {
			$this->updateBool('readed', 'id = '.$id);
			$data['readed'] = 1;
		}
		return $data;
	}
	
	public function findTitle() {
		return $this->find(array(
				'limit' => '0,6',
				'order' => 'readed asc,cdate desc'
						), 'id,title,readed,cdate');
	}
	
	public function findNoReaded() {
		return $this->findOne('readed = 0', 'COUNT(*) AS count')['count'];
		
	}
}