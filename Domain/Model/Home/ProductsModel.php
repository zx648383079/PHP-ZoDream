<?php
namespace Domain\Model\Home;

use Zodream\Domain\Html\Page;
use Zodream\Domain\Model;
class ProductsModel extends Model {
	protected $table = 'products';
	
	protected $fillAble = array(
		'title',
		'image',
		'keyword',
		'description',
		'content',
		'class_id',
		'user_id',
		'comment_count',
		'status',
		'allow_comment',
		'template',
		'update_at',
		'create_at'
	);
	
	public function findPage() {
		$page = new Page($this->count());
		$page->setPage($this->find(array(
				'order' => 'create_at desc',
				'limit' => $page->getLimit()
			), 'id,title,keyword,description'
		));
		return $page;
	}
}