<?php
namespace Domain\Model\Home;

use Domain\Model\Model;
use Zodream\Domain\Html\Page;

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
				'limit' => $page->getLimit()
			)
		));
		return $page;
	}
}