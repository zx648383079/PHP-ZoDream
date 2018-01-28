<?php
namespace Module\WeChat\Domain\Model;

use Domain\Model\Model;


/**
 * 微信公众号用户资料表
 * 从公众号中拉取的数据可以保存在此表
 * @property integer $id
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
            'nickname' => 'required|string:3-20',
            'sex' => 'required|int:0-1',
            'city' => 'required|string:3-40',
            'country' => 'required|string:3-40',
            'province' => 'required|string:3-40',
            'language' => 'required|string:3-40',
            'avatar' => 'required|string:3-255',
            'subscribe_time' => 'int',
            'union_id' => 'required|string:3-30',
            'remark' => 'required|string:3-255',
            'group_id' => 'required|string:3-5',
            'updated_at' => 'int',
        ];
    }

    /**
     * @inheritdoc
     */
    public function labels() {
        return [
            'id' => '粉丝ID',
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