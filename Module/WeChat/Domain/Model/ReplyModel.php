<?php
namespace Module\WeChat\Domain\Model;

use Domain\Model\Model;


/**
 * 微信公众号用户资料表
 * 从公众号中拉取的数据可以保存在此表
 * @property integer $id
 * @property integer $wid
 * @property string $event
 * @property string $keywords
 * @property string $content
 * @property string $type
 * @property integer $created_at
 * @property integer $updated_at
 */
class ReplyModel extends Model {
    /**
     * 激活状态
     */
    const STATUS_ACTIVE = 1;
    /**
     * 禁用状态
     */
    const STATUS_DISABLED = 0;
    const PROCESSOR_DEFAULT = 'process';
    public static $statuses = [
        self::STATUS_ACTIVE => '启用',
        self::STATUS_DISABLED => '禁用'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'wechat_reply';
    }

    protected function rules() {
        return [
            'wid' => 'required|int',
            'event' => 'required|string:3-20',
            'keywords' => 'required|string:3-60',
            'content' => 'required',
            'type' => 'required|string:3-10',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    /**
     * @inheritdoc
     */
    public function labels() {
        return [
            'id' => 'ID',
            'wid' => '所属微信公众号ID',
            'priority' => '优先级',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',

            'keywords' => '触发关键字'
        ];
    }

    /**
     * 回复的关键字
     * @return static
     */
    public function getKeywords() {
        return $this->hasMany(ReplyRuleKeyword::className(), ['rid' => 'id'])
            ->inverseOf('rule');
    }
}