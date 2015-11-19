<?php 
namespace App\Model;

use App\Lib\Object\OTime;

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
		if(empty($ml)) {
			return;
		}
		return $this->add(array(
			'url'   => App::url(),
			'event' => 'wechat',
			'data'  => $xml,
			'cdate' => OTime::Now()
		));
	}
}