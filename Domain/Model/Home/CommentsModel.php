<?php
namespace Domain\Model\Home;

use Zodream\Domain\Html\Page;
use Zodream\Domain\Model;
use Zodream\Infrastructure\ObjectExpand\TimeExpand;
class CommentsModel extends Model {
	protected $table = 'comments';
	
	protected $fillable = array(
		'content',
		'name',
		'email',
		'ip',
		'user_id',
		'post_id',
		'parent_id',
		'create_at'
	);

	public function findByPost($id) {
		return $this->find(array(
			'where' => 'posts_id = '.$id
		));
	}

	public function findPage($pageSize = 10) {
		$page = new Page($this->count());
		$page->setPage($this->findByHelper(array(
			'select' => 'c.id as id,c.content as content,c.ip as ip,c.name as name,c.email as email,p.title as post,c.create_at as create_at',
			'from' => 'comments c',
			'left' => array(
				'posts p',
				'c.posts_id = p.id'
			),
			'order' => 'c.create_at desc',
			'limit' => $page->getLimit($pageSize)
		)));
		return $page;
	}

	public function findNewComments() {
		list($start, $end) = TimeExpand::getBeginAndEndTime(TimeExpand::TODAY);
		return $this->count(array(
			"create_at between $start and $end"
		));
	}
}