<?php 
namespace App\Model;
/*********************************
*访问纪录
*create table zx_logs ( 
	id int(11) not null AUTO_INCREMENT PRIMARY KEY, 
	ip varchar(100),
	url varchar(255),
	event varchar(50) not null UNIQUE,
	data text,
	cdate int(11) 
)charset = utf8;
*********************************/
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