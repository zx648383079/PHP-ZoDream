<?php
namespace Module\MessageService\Domain\Entities;


use Domain\Entities\Entity;

/**
 *
 * @property integer $id
 * @property string $title
 * @property string $name
 * @property integer $type
 * @property string $data
 * @property string $content
 * @property string $target_no
 * @property integer $updated_at
 * @property integer $created_at
 */
class TemplateEntity extends Entity {
    public static function tableName(): string {
        return 'ms_template';
    }

    protected function rules(): array {
        return [
            'title' => 'required|string:0,100',
            'name' => 'required|string:0,20',
            'type' => 'int:0,127',
            'data' => 'required|string:0,255',
            'content' => 'required',
            'target_no' => 'string:0,32',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'title' => '标题',
            'name' => '唯一代码',
            'type' => '类型',
            'data' => '数据',
            'content' => '内容',
            'target_no' => '外部编号',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}