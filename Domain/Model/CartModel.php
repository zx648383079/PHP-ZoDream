<?php
namespace Domain\Model;

use Zodream\Domain\Model;
class CartModel extends Model {
	protected $table = 'cart';
	
	public function findCartInfo() {
		return $this->find(array(
				'where' => array(
						'session_id =',
						"rec_type = '{$type}'"
				),
				'order' => 'vieworder'
		), 'SUM(goods_number) AS number, SUM(goods_price * goods_number) AS amount');
	}
}