<?php
namespace App\Model;

class AccountModel extends Model {
	protected $table = "account";
	
	protected $fillable = array(
		'openid',
		'name',
		'cdate'
	);
	
	public function addOpenID($openid) {
		return $this->fill(array(
			'openid' => $openid
		));
	}
	
	public function deleteByOpenID($openid) {
		return $this->delete('openid = '. $openid);
	}
	
	public function findId($openid) {
		return $this->findOne('openid = '.$openid, 'id')['id'];
	}
}