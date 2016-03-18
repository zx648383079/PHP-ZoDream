<?php
namespace Domain\Model\Home;

use Zodream\Domain\Model;
class BlogCommentsModel extends Model {
	protected $table = 'blog_comments';
	
	protected $fillAble = array(
		'content',
		'name',
		'email',
		'ip',
		'user_id',
		'blog_id',
		'parent_id',
		'create_at'
	);
	
	public function findPage() {
		$page = new Page($this->count());
		$page->setPage($this->find(array(
				'order' => 'create_at desc',
				'limit' => $page->getLimit()
			)
		));
		return $page;
	}
}