<?php
namespace Module\MessageService\Domain\Entities;


use Domain\Entities\Entity;

/**
 *
 * @property integer $id
 * @property integer $template_id
 * @property integer $target_type
 * @property string $target
 * @property string $template_name
 * @property integer $type
 * @property string $content
 * @property integer $status
 * @property string $message
 * @property string $ip
 * @property integer $updated_at
 * @property integer $created_at
 */
class LogEntity extends Entity {
    public static function tableName(): string {
        return 'ms_log';
    }

    protected function rules(): array {
        return [
            'template_id' => 'int',
            'target_type' => 'required|int:0,127',
            'target' => 'required|string:0,100',
            'template_name' => 'required|string:0,20',
            'type' => 'int:0,127',
            'content' => 'required',
            'status' => 'int:0,127',
            'message' => 'string:0,255',
            'ip' => 'string:0,120',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'template_id' => 'Template Id',
            'target_type' => 'Target Type',
            'target' => 'Target',
            'template_name' => 'Template Name',
            'type' => 'Type',
            'content' => 'Content',
            'status' => 'Status',
            'message' => 'Message',
            'ip' => 'Ip',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}