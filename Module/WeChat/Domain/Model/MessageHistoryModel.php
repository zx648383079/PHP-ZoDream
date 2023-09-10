<?php
namespace Module\WeChat\Domain\Model;

use Domain\Model\Model;


/**
 * 微信请求消息历史记录
 * @property integer $id
 * @property integer $wid
 * @property integer $type
 * @property string $from
 * @property string $to
 * @property integer $created_at
 * @property integer $item_type
 * @property integer $item_id
 * @property string $content
 * @property integer $is_mark
 */
class MessageHistoryModel extends Model {
    /**
     * 微信请求信息
     */
    const TYPE_REQUEST = 1;
    /**
     * 微信请求后的系统响应信息
     */
    const TYPE_RESPONSE = 2;
    /**
     * 主动客服消息
     */
    const TYPE_CUSTOM = 3;
    /**
     * @inheritdoc
     */
    public static function tableName(): string {
        return 'wechat_message_history';
    }

    protected function rules(): array {
        return [
            'wid' => 'required|int',
            'from' => 'required|string:0,50',
            'to' => 'required|string:0,50',
            'created_at' => 'int',
            'type' => 'int:0,127',
            'item_type' => 'int:0,127',
            'item_id' => 'int',
            'content' => '',
            'is_mark' => 'int:0,127',
        ];
    }


    /**
     * @inheritdoc
     */
    public function labels(): array {
        return [
            'id' => 'ID',
            'wid' => '所属微信公众号ID',
            'rid' => '相应规则ID',
            'kid' => '所属关键字ID',
            'from' => '请求用户ID',
            'to' => '相应用户ID',
            'message' => '消息体内容',
            'type' => '发送类型',
            'created_at' => '创建时间',
        ];
    }

    public function from_user() {
        return $this->hasOne(UserModel::class, 'openid', 'from')
            ->select('id', 'openid', 'nickname', 'note_name', 'avatar');
    }

    public function to_user() {
        return $this->hasOne(UserModel::class, 'openid', 'to')
            ->select('id', 'openid', 'nickname', 'note_name', 'avatar');
    }
}