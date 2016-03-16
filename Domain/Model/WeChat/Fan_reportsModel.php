<?php
namespace Domain\Model\WeChat;

use Zodream\Domain\Model;
class Fan_reportsModel extends Model {
	protected $table = 'fan_reports';
	
	protected $fillable = array(
		'account_id',
		'openid',
		'type',
		'created_at',
		'updated_at'
	);
	
	public function findPage($search, $start, $count) {
		return $this->find(array(
				'where' => " like '%{$search}%'",
				'limit' => $start.','.$count,
				'order' => ' desc' 
		));
	}
}