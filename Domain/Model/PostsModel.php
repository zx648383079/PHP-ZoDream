<?php
namespace Domain\Model;

use Zodream\Domain\Model;
use Zodream\Domain\Html\Page;
class PostsModel extends Model {
	protected $table = 'posts';
	
	protected $fillable = array(
		'title',
		'content',
		'kind',
		'comment_count',
		'user_id',
		'premalink',
		'udate',
		'cdate'
	);
	
	public function findPage($kind = null, $pageSize = 10) {
		$page = new Page($this->count(empty($kind) ? : ('kind = '.$kind)));
		$sql = array(
				'select' => 'p.id as id,p.title as title,p.kind as kind,p.content as content,u.username as user,p.udate as udate,p.cdate as cdate',
				'from' => 'posts p',
				'left' => array(
						'users u',
						'p.user_id = u.id'
				),
				'where' => 'p.kind = '. $kind,
				'order' => 'p.cdate desc',
				'limit' => $page->getLimit($pageSize)
		);
		if (empty($kind)) {
			unset($sql['where']);
		}
		$page->setPage($this->findByHelper($sql));
		return $page;
	}
	
	public function findProducts() {
		return $this->find(array(
				'where' => 'kind = 2'
		), 'id,content');
	}
	
	public function findNewProduct() {
		$results = $this->find(array(
			'where' => 'kind = 2',
			'order' => 'cdate desc',
			'limit' => 1
		), 'id,title,content');
		return array_shift($results);
	}
	
	public function findService() {
		return $this->find(array(
				'where' => 'kind = 1'
		), 'id,title,content');
	}
	
	public function findNewService() {
		return $this->find(array(
				'where' => 'kind = 1',
				'order' => 'cdate desc',
				'limit' => 2
		), 'id,title,content');
	}
	
	public function findBlog() {
		return $this->find(array(
				'where' => 'kind = 3',
				'order' => 'comment_count desc',
				'limit' => 2
		), 'id,title,content,comment_count,cdate');
	}
	
	public function findNew() {
		return $this->find(array(
				'order' => 'cdate desc',
				'limit' => 3
		), 'id,title,content');
	}

	public function findDownload() {
		return $this->find(array(
			'where' => 'kind = 4',
			'order' => 'cdate desc',
			'limit' => 6
		), 'id,title,content');
	}

}