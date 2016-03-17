<?php
namespace Domain\Model\Home;

use Zodream\Domain\Model;
class CityModel extends Model {
	protected $table = 'city';
	
	protected $fillable = array(
		'parent_id',
		'name',
		'level'
	);
	
	public function findPage($search, $start, $count) {
		return $this->find(array(
				'where' => " like '%{$search}%'",
				'limit' => $start.','.$count,
				'order' => ' desc' 
		));
	}
}