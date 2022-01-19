<?php
namespace Module\WeChat\Domain\Model;

use Domain\Model\Model;


/**
 * 微信公众号用户资料表
 * 从公众号中拉取的数据可以保存在此表
 * @property integer $id
 * @property string $openid
 * @property string $nickname
 * @property integer $sex
 * @property string $city
 * @property string $country
 * @property string $province
 * @property string $language
 * @property string $avatar
 * @property integer $subscribe_time
 * @property string $union_id
 * @property string $remark
 * @property integer $group_id
 * @property integer $updated_at
 */
class UserModel extends Model {
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'wechat_user';
    }

    protected function rules() {
        return [
            'openid' => 'required|string:0,50',
            'nickname' => 'string:0,20',
            'sex' => 'int:0,9',
            'city' => 'string:0,40',
            'country' => 'string:0,40',
            'province' => 'string:0,40',
            'language' => 'string:0,40',
            'avatar' => 'string:0,255',
            'subscribe_time' => 'int',
            'union_id' => 'string:0,30',
            'remark' => 'string:0,255',
            'group_id' => 'string:0,5',
            'updated_at' => 'int',
        ];
    }

    /**
     * @inheritdoc
     */
    public function labels() {
        return [
            'id' => '粉丝ID',
            'openid' => '微信ID',
            'nickname' => '昵称',
            'sex' => '性别',
            'city' => '所在城市',
            'country' => '所在省',
            'province' => '微信ID',
            'language' => '用户语言',
            'avatar' => '用户头像',
            'subscribe_time' => '关注时间',
            'union_id' => '用户头像',
            'remark' => '备注',
            'group_id' => '分组ID',
            'updated_at' => '修改时间',
        ];
    }
}