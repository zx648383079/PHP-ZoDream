<?php
namespace Module\WeChat\Domain\Model;

use Domain\Model\Model;
use Zodream\Infrastructure\Http\Request;


/**
 * 微信公众号用户资料表
 * 从公众号中拉取的数据可以保存在此表
 * @property integer $id
 * @property integer $wid
 * @property string $event
 * @property string $keywords
 * @property integer $match
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
            'keywords' => 'string:3-60',
            'content' => 'required',
            'match' => 'int',
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

    public function loadEditor() {
        $data = Request::post('editor');
        $this->type = intval($data['type']);
        if ($this->type == 0) {
            $this->content = $data['text'];
            return;
        }
    }
}