<?php
namespace Domain\Model\WeChat;

use Domain\Model\Model;
/**
* Class WechatResourceModel
* @property integer $id
* @property integer $wechat_id
* @property string $detail
* @property string $type
* @property integer $status
* @property integer $update_at
* @property integer $create_at
*/
class WechatResourceModel extends Model {
	public static $table = 'wechat_resource';

	protected function rules() {
		return array (
		  'id' => 'required|int',
		  'wechat_id' => 'int',
		  'detail' => '',
		  'type' => '',
		  'status' => 'int:0-1',
		  'update_at' => 'int',
		  'create_at' => 'int',
		);
	}

	protected function labels() {
		return array (
		  'id' => 'Id',
		  'wechat_id' => 'Wechat Id',
		  'detail' => 'Detail',
		  'type' => 'Type',
		  'status' => 'Status',
		  'update_at' => 'Update At',
		  'create_at' => 'Create At',
		);
	}
}