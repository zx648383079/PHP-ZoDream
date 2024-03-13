<?php
namespace Module\Bot\Domain\Model;

/**
 *
 * @property integer $id
 * @property integer $bot_id
 * @property string $event
 * @property string $keywords
 * @property integer $match
 * @property string $content
 * @property integer $type
 * @property integer $updated_at
 * @property integer $created_at
 * @property integer $status
 */
class ReplyModel extends EditorModel {

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
    public static function tableName(): string {
        return 'bot_reply';
    }

    protected function rules(): array {
        return [
            'bot_id' => 'required|int',
            'event' => 'required|string:0,20',
            'keywords' => 'string:0,60',
            'match' => 'int:0,127',
            'content' => 'required',
            'type' => 'int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
            'status' => 'int:0,127',
        ];
    }

    /**
     * @inheritdoc
     */
    public function labels(): array {
        return [
            'id' => 'ID',
            'bot_id' => '所属微信公众号ID',
            'event' => '事件',
            'event_name' => '事件名',
            'keywords' => '关键字',
            'match' => '匹配方式',
            'content' => 'Content',
            'type' => 'Type',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
        ];
    }

}