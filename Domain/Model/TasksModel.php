<?php
namespace Domain\Model;

use Zodream\Domain\Html\Page;
use Zodream\Domain\Model;
class TasksModel extends Model {
	protected $table = 'tasks';
	
	protected $fillable = array(
		'name',
		'content',
		'progress',
		'status',
		'user_id',
		'udate',
		'cdate'
	);

	public function findPage($pageSize = 10) {
		$page = new Page($this->count());
		$sql = array(
			'select' => 't.id as id,t.name as name,t.status as status,t.progress as progress,u.username as user,t.udate as udate,t.cdate as cdate',
			'from' => 'tasks t',
			'left' => array(
				'users u',
				't.user_id = u.id'
			),
			'order' => 't.cdate desc',
			'limit' => $page->getLimit($pageSize)
		);
		$page->setPage($this->findByHelper($sql));
		return $page;
	}

	public function findPageWithFront($pageSize = 10) {
		$page = new Page($this->count());
		$sql = array(
			'select' => 't.id as id,t.name as name,t.status as status,t.progress as progress,u.username as user,t.udate as udate,t.cdate as cdate',
			'from' => 'tasks t',
			'left' => array(
				'users u',
				't.user_id = u.id'
			),
			'where' => 't.status > 0',
			'order' => 't.progress desc',
			'limit' => $page->getLimit($pageSize)
		);
		$page->setPage($this->findByHelper($sql));
		return $page;
	}

	public function findNewTasks() {
		return $this->count(array(
			'status = 0'
		));
	}

}