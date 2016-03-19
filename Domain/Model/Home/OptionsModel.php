<?php
namespace Domain\Model\Home;

use Zodream\Domain\Model;
class OptionsModel extends Model {
	protected $table = 'options';
	
	protected $fillAble = array(
		'name',
		'value'
	);

	public function findSingle() {
		return $this->find(array(
			'where' => array(
				'name = \'index\'',
				array('name = \'about\'', 'or')
			)
		));
	}

	public function findPage($page = 'index') {
		return $this->find(array(
			'where' => array(
				"name = '{$page}'"
			)
		));
	}

	public function findValue() {
		$data = $this->find(array(
			'where' => array(
				'name != \'index\'',
				array('name != \'about\'', 'or')
			)
		));
		$results = array();
		foreach ($data as $value) {
			$results[$value['name']] = $value['value'];
		}
		return $results;
	}
}