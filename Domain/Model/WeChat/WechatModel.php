<?php
namespace Domain\Model\WeChat;

use Domain\Model\Model;
/**
* Class WechatModel
* @property integer $id
* @property string $name
* @property string $original_id
* @property string $app_id
* @property string $app_secret
* @property string $token
* @property string $aes_key
* @property string $account
* @property string $tag
* @property string $access_token
* @property integer $type
* @property integer $status
*/
class WechatModel extends Model {
	public static $table = 'wechat';

	protected $primaryKey = array (
	  'id',
	  'tag',
	);

	protected function rules() {
		return array (
		  'name' => 'required|string:3-60',
		  'original_id' => 'required|string:3-20',
		  'app_id' => 'string:3-50',
		  'app_secret' => 'string:3-50',
		  'token' => 'string:3-10',
		  'aes_key' => 'string:3-45',
		  'account' => 'string:3-20',
		  'tag' => 'string:3-20',
		  'access_token' => 'string:3-30',
		  'type' => 'int',
		  'status' => 'int:0-1',
		);
	}

	protected function labels() {
		return array (
		  'id' => 'Id',
		  'name' => 'Name',
		  'original_id' => 'Original Id',
		  'app_id' => 'App Id',
		  'app_secret' => 'App Secret',
		  'token' => 'Token',
		  'aes_key' => 'Aes Key',
		  'account' => 'Account',
		  'tag' => 'Tag',
		  'access_token' => 'Access Token',
		  'type' => 'Type',
		  'status' => 'Status',
		);
	}
}