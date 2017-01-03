<?php
namespace Domain\Model\WeChat;

use Domain\Model\Model;
/**
* Class WechatReplyModel
* @property integer $id
* @property integer $wechat_id
* @property string $type
* @property string $name
* @property string $trigger_keyword
* @property string $trigger_type
* @property string $content
* @property string $group_ids
* @property integer $update_at
* @property integer $create_at
*/
class WechatReplyModel extends Model {
	public static function tableName() {
        return 'wechat_reply';
    }

    protected function rules() {
		return array (
		  'wechat_id' => 'int',
		  'type' => '',
		  'name' => 'string:3-30',
		  'trigger_keyword' => 'string:3-500',
		  'trigger_type' => '',
		  'content' => 'string:3-255',
		  'group_ids' => 'string:3-45',
		  'update_at' => 'int',
		  'create_at' => 'int',
		);
	}

	protected function labels() {
		return array (
		  'id' => 'Id',
		  'wechat_id' => 'Wechat Id',
		  'type' => 'Type',
		  'name' => 'Name',
		  'trigger_keyword' => 'Trigger Keyword',
		  'trigger_type' => 'Trigger Type',
		  'content' => 'Content',
		  'group_ids' => 'Group Ids',
		  'update_at' => 'Update At',
		  'create_at' => 'Create At',
		);
	}
}