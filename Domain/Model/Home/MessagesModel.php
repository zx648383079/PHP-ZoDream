<?php
namespace Domain\Model\Home;

use Zodream\Domain\Html\Page;
use Domain\Model\Model;
class MessagesModel extends Model {
	protected $table = 'messages';
	
	protected $fillAble = array(
		'name',
		'email',
		'title',
		'content',
		'ip',
		'readed',
		'create_at'
	);

	public function findPage() {
		$page = new Page($this->count());
		$page->setPage($this->find(array(
				'order' => 'create_at desc',
				'limit' => $page->getLimit()
			), 'id,name,email,title,ip,readed,create_at'
		));
		return $page;
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
			'order' => 'readed asc,create_at desc'
		), 'id,title,readed,create_at');
	}

	public function findNoReaded() {
		return $this->findOne('readed = 0', 'COUNT(*) AS count')['count'];
	}
}