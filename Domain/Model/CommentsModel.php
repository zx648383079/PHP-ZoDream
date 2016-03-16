<?php
namespace Domain\Model;

use Zodream\Domain\Model;
use Zodream\Domain\Html\Page;
use Zodream\Infrastructure\ObjectExpand\TimeExpand;

class CommentsModel extends Model {
	protected $table = 'comments';
	
	protected $fillable = array(
		'content',
		'ip',
		'name',
		'email',
		'user_id',
		'posts_id',
		'parent_id',
		'cdate'
	);
	
	public function findByPost($id) {
		return $this->find(array(
				'where' => 'posts_id = '.$id
		));
	}
	
	public function findPage($pageSize = 10) {
		$page = new Page($this->count());
		$page->setPage($this->findByHelper(array(
				'select' => 'c.id as id,c.content as content,c.ip as ip,c.name as name,c.email as email,p.title as post,c.cdate as cdate',
				'from' => 'comments c',
				'left' => array(
						'posts p',
						'c.posts_id = p.id'
				),
				'order' => 'c.cdate desc',
				'limit' => $page->getLimit($pageSize)
		)));
		return $page;
	}

	public function findNewComments() {
		list($start, $end) = TimeExpand::getBeginAndEndTime(TimeExpand::TODAY);
		return $this->count(array(
			"cdate between $start and $end"
		));
	}
}