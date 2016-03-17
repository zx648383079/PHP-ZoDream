<?php
namespace Domain\Model\Home;

use Zodream\Domain\Html\Page;
use Zodream\Domain\Model;
class PostsModel extends Model {
	protected $table = 'posts';
	
	protected $fillable = array(
		'title',
		'image',
		'keyword',
		'description',
		'content',
		'class_id',
		'user_id',
		'kind',
		'comment_count',
		'template',
		'update_at',
		'create_at'
	);

	public function findPage() {
		$page = new Page($this->count());
		$sql = array(
			'select' => 'p.id as id,p.title as title,p.kind as kind,u.name as user,p.update_at as update_at,p.create_at as create_at',
			'from' => 'posts p',
			'left' => array(
				'users u',
				'p.user_id = u.id'
			),
			'order' => 'p.create_at desc',
			'limit' => $page->getLimit()
		);
		$page->setPage($this->findByHelper($sql));
		return $page;
	}
}