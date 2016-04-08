<?php
namespace Domain\Model\Home;


use Domain\Model\Model;
use Zodream\Domain\Html\Page;
class BlogsModel extends Model {
	protected $table = 'blogs';
	
	protected $fillAble = array(
		'title',
		'image',
		'keyword',
		'description',
		'content',
		'category_id',
		'user_id',
		'comment_count',
		'status',
		'allow_comment',
		'template',
		'update_at',
		'create_at'
	);
	
	public function findPage($category = null) {
		$where = empty($category) ? null : 'category_id = '.$category;
		$page = new Page($this->count($where));
		$sql = array(
			'select' => 'b.id as id,b.title as title,b.keyword as keyword,b.description as description,c.name as category,u.name as user,b.update_at as update_at,b.create_at as create_at',
			'from' => 'blogs b',
			'left' => array(
				'blog_category c',
				'c.id = b.category_id'
			),
			'left`' => array(
				'users u',
				'u.id = b.user_id'
			),
			'where' => $where,
			'order' => 'b.create_at desc',
			'limit' => $page->getLimit()
		);
		$page->setPage($this->findByHelper($sql));
		return $page;
	}

	public function findView($id) {
		return $this->findByHelper(
			array(
				'select' => 'b.id as id,b.title as title,b.image as image,b.keyword as keyword,b.description as description,b.content as content,c.name as category,u.name as user,b.allow_comment as allow_comment,b.update_at as update_at,b.create_at as create_at',
				'from' => 'blogs b',
				'left' => array(
					'blog_category c',
					'c.id = b.category_id'
				),
				'left`' => array(
					'users u',
					'u.id = b.user_id'
				),
				'where' => 'b.id = '.$id,
				'limit' => 1
			)
		)[0];
	}

	public function getNextAndBefore($id) {
		$before = $this->find(array(
			'where' => array(
				'id < '.$id,
				'status = 1'
			),
			'order' => 'id desc',
			'limit' => 1
		), 'id,title');
		return array(
			count($before) == 1 ? $before[0] : null,
			$this->findOne(array(
				'id > '.$id,
				'status = 1'
			), 'id,title')
		);
	}

	public function getHot() {
		return $this->find(array(
				'order' => 'comment_count desc',
				'limit' => 4
			), 'id,image,title,description,create_at'
		);
	}
}