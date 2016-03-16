<?php
namespace Domain\Model;

use Zodream\Domain\Model;
class NavModel extends Model {
	protected $table = 'nav';
	
	public function findNav($type = 'top') {
		return $this->find(array(
				'where' => array(
						'ifshow = 1',
						"type = '{$type}'"
				),
				'order' => 'vieworder'
		));
	}
}