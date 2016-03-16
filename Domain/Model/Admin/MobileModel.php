<?php
namespace Domain\Model\Admin;

use Zodream\Domain\Model;
class MobileModel extends Model {
	protected $table = 'mobile';
	
	protected $fillable = array(
		'id',
		'number',
		'city',
		'type',
		'city_code',
		'postcode'
	);
	
	public function findNumber($number) {
		return $this->findOne("number = '{$number}'");
	}
}