<?php
namespace Module\WeChat\Domain\Model;

use Domain\Model\Model;
use Zodream\ThirdParty\WeChat\User;


/**
 * Class FansModel
 * @property integer $id
 * @property integer $wid
 * @property string $openid
 * @property integer $status
 * @property integer $is_back
 * @property integer $created_at
 * @property integer $updated_at
 * @property WeChatModel $wechat
 * @property UserModel $user
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
    public static function tableName() {
        return 'wechat_fans';
    }

    protected function rules() {
        return [
            'wid' => 'required|int',
            'openid' => 'required|string:0,50',
            'status' => 'required|int:0,9',
            'is_back' => 'required|int:0,9',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'ID',
            'wid' => '所属微信公众号ID',
            'openid' => '微信ID',
            'status' => '关注状态',
            'is_back' => '是否是黑名单',
            'created_at' => '关注时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * 关联公众号
     *
     */
    public function weChat() {
        return $this->hasOne(WeChatModel::class, 'id', 'wid');
    }

    /**
     * 关联的用户信息
     */
    public function user() {
        return $this->hasOne(UserModel::class, 'id', 'id');
    }

    public function getStatusLabelAttribute() {
        return self::$statuses[$this->status];
    }

    /**
     * 通过唯一的openid查询粉丝
     * @param $open_id
     * @return static
     */
    public static function findByOpenId($open_id) {
        return self::where('openid', $open_id)->first();
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
     * @throws \Exception
     */
    public function updateUser($force = false) {
        $user = $this->user;
        if (!$user || $force) {
            $wechat = $this->wechat;
            $user = new UserModel();
            $data = $wechat->sdk(User::class)->userInfo($this->openid);
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