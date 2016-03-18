<?php
namespace Domain\Model\Home;

use Zodream\Domain\Model;
class MobileModel extends Model {
	protected $table = 'mobile';
	
	protected $fillAble = array(
		'id',
		'number',
		'city',
		'type',
		'city_code',
		'postcode'
	);
	
	public function findPage($search, $start, $count) {
		return $this->find(array(
				'where' => " like '%{$search}%'",
				'limit' => $start.','.$count,
				'order' => ' desc' 
		));
	}
}