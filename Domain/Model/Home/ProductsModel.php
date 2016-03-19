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
		$page->setPage($this->findByHelper(
			array(
				'select' => 'p.id as id,p.title as title,p.keyword as keyword,p.description as description,c.name as category,u.name as user,p.update_at as update_at,p.create_at as create_at',
				'from' => 'products p',
				'left' => array(
					'product_classes c',
					'c.id = p.class_id'
				),
				'left`' => array(
					'users u',
					'u.id = p.user_id'
				),
				'order' => 'p.create_at desc',
				'limit' => $page->getLimit()
			)
		));
		return $page;
	}

	public function findView($id) {
		return $this->findByHelper(
			array(
				'select' => 'p.id as id,p.title as title,p.image as image,p.keyword as keyword,p.description as description,p.content as content,c.name as category,u.name as user,p.update_at as update_at,p.create_at as create_at',
				'from' => 'products p',
				'left' => array(
					'product_classes c',
					'c.id = p.class_id'
				),
				'left`' => array(
					'users u',
					'u.id = p.user_id'
				),
				'where' => 'p.id = '.$id,
				'limit' => 1
			)
		)[0];
	}
}