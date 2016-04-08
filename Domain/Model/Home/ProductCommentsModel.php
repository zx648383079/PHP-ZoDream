<?php
namespace Domain\Model\Home;

use Domain\Model\Model;
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

	public function findPage($id, $page) {
		$where = 'product_id = '.$id;
		$count = $this->count($where);
		if ($page < 1) {
			$page = 1;
		}
		$start = $page * 10 - 10;
		return array(
			'more' => $count - $start > 10,
			'data' => $this->find(array(
					'where' => $where,
					'order' => 'create_at desc',
					'limit' => $start.',10'
				), 'id,name,content,parent_id,create_at'
			)
		);
	}
}