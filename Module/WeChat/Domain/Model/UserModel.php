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
 * @property integer $subscribe_at
 * @property string $union_id
 * @property string $remark
 * @property integer $group_id
 * @property integer $wid
 * @property string $note_name
 * @property integer $status
 * @property integer $is_black
 * @property integer $updated_at
 * @property integer $created_at
 */
class UserModel extends Model {

    /**
     * 取消关注
     */
    const STATUS_UNSUBSCRIBED = 0;
    /**
     * 关注状态
     */
    const STATUS_SUBSCRIBED = 1;

    public static function tableName(): string {
        return 'wechat_user';
    }

    protected function rules(): array {
        return [
            'wid' => 'required|int',
            'openid' => 'required|string:0,50',
            'nickname' => 'string:0,20',
            'sex' => 'int:0,127',
            'city' => 'string:0,40',
            'country' => 'string:0,40',
            'province' => 'string:0,40',
            'language' => 'string:0,40',
            'avatar' => 'string:0,255',
            'subscribe_at' => 'int',
            'union_id' => 'string:0,30',
            'remark' => 'string:0,255',
            'group_id' => 'int',
            'note_name' => 'string:0,20',
            'status' => 'int:0,127',
            'is_black' => 'int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    /**
     * @inheritdoc
     */
    public function labels(): array {
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
            'note_name' => '备注名',
            'status' => '是否关注',
            'is_black' => '是否黑名单',
            'updated_at' => '修改时间',
        ];
    }

    public function group() {
        return $this->hasOne(UserGroupModel::class, 'id', 'group_id');
    }
}