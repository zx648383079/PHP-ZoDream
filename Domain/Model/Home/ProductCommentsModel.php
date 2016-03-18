<?php
namespace Domain\Model\Home;

use Zodream\Domain\Model;
class ProductCommentsModel extends Model {
	protected $table = 'product_comments';
	
	protected $fillAble = array(
		'content',
		'name',
		'email',
		'ip',
		'user_id',
		'product_id',
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