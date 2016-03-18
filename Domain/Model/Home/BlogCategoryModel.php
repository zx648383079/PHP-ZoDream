<?php
namespace Domain\Model\Home;

use Zodream\Domain\Model;
use Zodream\Domain\Html\Page;
class BlogCategoryModel extends Model {
	protected $table = 'blog_category';
	
	protected $fillAble = array(
		'name',
		'description',
		'status',
		'user_id'
	);
	
	public function findPage() {
		$page = new Page($this->count());
		$page->setPage($this->find(array(
				'limit' => $page->getLimit()
			)
		));
		return $page;
	}
}