<?php
namespace Domain\Model\Home;

use Zodream\Domain\Model;
class ProductClassesModel extends Model {
	protected $table = 'product_classes';
	
	protected $fillAble = array(
		'name',
		'description',
		'status'
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