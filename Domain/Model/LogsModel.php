<?php
namespace Domain\Model;

use Zodream\Domain\Model;
use Zodream\Domain\Routing\UrlGenerator;
use Zodream\Infrastructure\Request;

class LogsModel extends Model {
	protected $table = 'logs';
	
	protected $fillable = array(
		'action',
		'data',
		'url',
		'ip',
		'cdate'
	);

	/**
	 * 添加纪录
	 * @param $data
	 * @param $action
	 * @return int
	 */
	public function addLog($data, $action) {
		return $this->add(array(
			'action' => $action,
			'data' => is_string($data) ? $data : json_encode($data),
			'url' => UrlGenerator::to(),
			'ip' => Request::ip(),
			'cdate' => time()
		));
	}

}