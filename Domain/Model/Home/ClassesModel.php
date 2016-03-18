<?php
namespace Domain\Model\Home;

use Zodream\Domain\Model;
class ClassesModel extends Model {
	protected $table = 'classes';
	
	protected $fillable = array(
		'id',
		'name',
		'kind'
	);
	
	public function findPage($search, $start, $count) {
		return $this->find(array(
				'where' => " like '%{$search}%'",
				'limit' => $start.','.$count,
				'order' => ' desc' 
		));
	}

	public function findByKind($kind = 'blog') {
		return $this->find(array(
			'where' => "kind = '{$kind}'"
		), 'id,name');
	}
}