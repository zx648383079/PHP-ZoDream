<?php
namespace Domain\Model\WeChat;

use Domain\Model\Model;
/**
* Class WechatGroupModel
* @property integer $id
* @property integer $wechat_id
* @property integer $parent
* @property string $name
* @property integer $fan_count
* @property integer $is_default
* @property integer $update_at
* @property integer $create_at
*/
class WechatGroupModel extends Model {
	public static function tableName() {
        return 'wechat_group';
    }

    protected function rules() {
		return array (
		  'wechat_id' => 'int',
		  'parent' => 'int',
		  'name' => 'string:3-90',
		  'fan_count' => 'int',
		  'is_default' => 'int:0-1',
		  'update_at' => 'int',
		  'create_at' => 'int',
		);
	}

	protected function labels() {
		return array (
		  'id' => 'Id',
		  'wechat_id' => 'Wechat Id',
		  'parent' => 'Parent',
		  'name' => 'Name',
		  'fan_count' => 'Fan Count',
		  'is_default' => 'Is Default',
		  'update_at' => 'Update At',
		  'create_at' => 'Create At',
		);
	}
}