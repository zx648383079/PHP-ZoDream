<?php 
namespace App\Model;

use App\Lib\Object\OTime;
use App\Lib\Helper\HUrl;
use App\Lib\Helper\HIp;

class LogModel extends Model
{
	protected static $instance;
	
	protected $table = "logs";
	
	protected $fillable = array(
		'ip',
		'url',
		'event',
		'data',
		'cdate'
	);
	
	public static function getInstance() {
		if (!(self::$instance instanceof self)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	public function addWechat($xml) {
		if (empty($xml)) {
			return;
		}
		return $this->add(array(
			'ip'    => HIp::getIp(),
			'url'   => HUrl::to(),
			'event' => 'wechat',
			'data'  => $xml,
			'cdate' => OTime::Now()
		));
	}
}