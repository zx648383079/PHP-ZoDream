<?php
namespace App\Model;


class MessageModel extends Model
{
	protected $table = "message";
	
	protected $fillable = array(
		'keyword',
		'content',
		'account_id',
		'cdate'
	);
	
	public function findByKeyword($arg) {
		return $this->findList("keyword like '%{$arg}%'");
	}
}