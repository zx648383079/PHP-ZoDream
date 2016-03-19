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
	
	public function findPage() {
		$page = new Page($this->count());
		$page->setPage($this->findByHelper(
			array(
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
				'order' => 'b.create_at desc',
				'limit' => $page->getLimit()
			)
		));
		return $page;
	}

	public function findView($id) {
		return $this->findByHelper(
			array(
				'select' => 'b.id as id,b.title as title,b.image as image,b.keyword as keyword,b.description as description,b.content as content,c.name as category,u.name as user,b.update_at as update_at,b.create_at as create_at',
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
}