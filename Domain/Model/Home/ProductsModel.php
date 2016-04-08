<?php
namespace Domain\Model\Home;

use Zodream\Domain\Html\Page;
use Domain\Model\Model;
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

	public function findPage($class = null) {
		$where = empty($class) ? null : 'class_id = '.$class;
		$page = new Page($this->count($where));
		$page->setPage($this->findByHelper(
			array(
				'select' => 'p.id as id,p.title as title,p.image as image,p.keyword as keyword,p.description as description,c.name as category,u.name as user,p.update_at as update_at,p.create_at as create_at',
				'from' => 'products p',
				'left' => array(
					'product_classes c',
					'c.id = p.class_id'
				),
				'left`' => array(
					'users u',
					'u.id = p.user_id'
				),
				'where' => $where,
				'order' => 'p.create_at desc',
				'limit' => $page->getLimit()
			)
		));
		return $page;
	}

	public function findView($id) {
		return $this->findByHelper(
			array(
				'select' => 'p.id as id,p.title as title,p.image as image,p.keyword as keyword,p.description as description,p.content as content,c.name as class,u.name as user,p.allow_comment as allow_comment,p.update_at as update_at,p.create_at as create_at',
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

	public function getNextAndBefore($id) {
		return array(
			$this->findOne(array(
				'id < '.$id,
				'status = 1'
			), 'id,title'),
			$this->findOne(array(
				'id > '.$id,
				'status = 1'
			), 'id,title')
		);
	}

	public function getNew() {
		$product = $this->find(array(
			'order' => 'create_at desc',
			'limit' => 1
		), 'id,image,title,description');
		return count($product) == 1 ? $product[0] : null;
	}

	public function getHot() {
		return $this->find(array(
			'order' => 'comment_count desc',
			'limit' => 10
		), 'id,image');
	}
}