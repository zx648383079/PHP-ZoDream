<?php
namespace Domain\Model\WeChat;

use Domain\Model\Model;
/**
* Class WechatFanModel
* @property integer $id
* @property integer $wechat_id
* @property integer $group_id
* @property string $openid
* @property string $nickname
* @property string $signature
* @property string $remark
* @property string $sex
* @property string $language
* @property string $city
* @property string $province
* @property string $country
* @property string $avatar
* @property integer $unionid
* @property integer $liveness
* @property integer $subscribed_at
* @property integer $update_at
* @property integer $create_at
* @property integer $deleted_at
*/
class WechatFanModel extends Model {
	public static function tableName() {
        return 'wechat_fan';
    }

    protected function rules() {
		return array (
		  'wechat_id' => 'int',
		  'group_id' => 'int',
		  'openid' => 'string:3-100',
		  'nickname' => 'string:3-300',
		  'signature' => 'string:3-300',
		  'remark' => '',
		  'sex' => '',
		  'language' => 'string:3-300',
		  'city' => 'string:3-300',
		  'province' => 'string:3-300',
		  'country' => 'string:3-300',
		  'avatar' => 'string:3-300',
		  'unionid' => 'int',
		  'liveness' => 'int',
		  'subscribed_at' => 'int',
		  'update_at' => 'int',
		  'create_at' => 'int',
		  'deleted_at' => 'int',
		);
	}

	protected function labels() {
		return array (
		  'id' => 'Id',
		  'wechat_id' => 'Wechat Id',
		  'group_id' => 'Group Id',
		  'openid' => 'Openid',
		  'nickname' => 'Nickname',
		  'signature' => 'Signature',
		  'remark' => 'Remark',
		  'sex' => 'Sex',
		  'language' => 'Language',
		  'city' => 'City',
		  'province' => 'Province',
		  'country' => 'Country',
		  'avatar' => 'Avatar',
		  'unionid' => 'Unionid',
		  'liveness' => 'Liveness',
		  'subscribed_at' => 'Subscribed At',
		  'update_at' => 'Update At',
		  'create_at' => 'Create At',
		  'deleted_at' => 'Deleted At',
		);
	}
}