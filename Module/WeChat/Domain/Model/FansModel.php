<?php
namespace Module\WeChat\Domain\Model;

use Domain\Model\Model;


/**
 * This is the model class for table "wechat_fans".
 *
 * @property integer $id
 * @property integer $wid
 * @property string $openid
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class FansModel extends Model {
    /**
     * 取消关注
     */
    const STATUS_UNSUBSCRIBED = -1;
    /**
     * 关注状态
     */
    const STATUS_SUBSCRIBED = 0;
    public static $statuses = [
        self::STATUS_SUBSCRIBED => '关注',
        self::STATUS_UNSUBSCRIBED => '取消关注'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wechat_fans';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['wid', 'openid'], 'required'],
            [['wid', 'status', 'created_at', 'updated_at'], 'integer'],
            [['open_id'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function labels() {
        return [
            'id' => 'ID',
            'wid' => '所属微信公众号ID',
            'openid' => '微信ID',
            'status' => '关注状态',
            'created_at' => '关注时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * 关联公众号
     *
     */
    public function getWeChat() {
        return $this->hasOne(WeChatModel::className(), ['id' => 'wid']);
    }

    /**
     * 关联的用户信息
     */
    public function getUser() {
        return $this->hasOne(UserModel::className(), ['id' => 'id']);
    }

    /**
     * 通过唯一的openid查询粉丝
     * @param $open_id
     * @return mixed
     */
    public static function findByOpenId($open_id) {
        return self::find(['openid' => $open_id]);
    }

    /**
     * 关注
     * @return bool
     */
    public function subscribe() {
        $this->status = self::STATUS_SUBSCRIBED;
        return $this->save() > 0;
    }

    /**
     * 取消关注
     * @return bool
     */
    public function unsubscribe() {
        $this->status = self::STATUS_UNSUBSCRIBED;
        return $this->save() > 0;
    }

    /**
     * 更新用户微信数据
     * 更新失败将会在$this->user->getErrors()中记录错误
     * @param bool $force
     * @return bool
     */
    public function updateUser($force = false) {
        $user = $this->user;
        if (!$user || $force) {
            $wechat = $this->wechat;
            $user = new UserModel();
            $this->populateRelation('user', $user);
            $data = $wechat->getSdk()->getUserInfo($this->open_id);
            if ($data) {
                $user->set([
                    'id' => $this->id,
                    'nickname' => $data['nickname'],
                    'sex' => $data['sex'],
                    'city' => $data['city'],
                    'country' => $data['country'],
                    'province' => $data['province'],
                    'language' => $data['language'],
                    'avatar' => $data['headimgurl'],
                    'subscribe_time' => $data['subscribe_time'],
                    'remark' => $data['remark'],
                    'union_id' => isset($data['unionid']) ? $data['unionid'] : '', // 测试号无此项
                    'group_id' => $data['groupid'],
                ]);
                return $user->save();
            }
            $user->setError('id', '用户资料更新失败!');
            return false;
        }
        return true;
    }
}