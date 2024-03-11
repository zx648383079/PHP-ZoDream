<?php
declare(strict_types=1);
namespace Module\AdSense\Domain\Entities;


use Domain\Entities\Entity;

/**
 *
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property integer $auto_size
 * @property integer $source_type
 * @property string $width
 * @property string $height
 * @property string $template
 * @property integer $status
 * @property integer $updated_at
 * @property integer $created_at
 */
class AdPositionEntity extends Entity {
    public static function tableName(): string {
        return 'ad_position';
    }

    protected function rules(): array {
        return [
            'name' => 'required|string:0,30',
            'code' => 'required|string:0,20',
            'auto_size' => 'int:0,127',
            'source_type' => 'int:0,127',
            'width' => 'string:0,10',
            'height' => 'string:0,10',
            'template' => 'string:0,500',
            'status' => 'int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => '名称',
            'code' => '调用代码',
            'auto_size' => '自适应',
            'source_type' => '来源类型',
            'width' => '宽',
            'height' => '高',
            'template' => '模板',
            'status' => '状态',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}