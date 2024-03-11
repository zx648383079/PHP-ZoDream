<?php
declare(strict_types=1);
namespace Module\AdSense\Domain\Entities;


use Domain\Entities\Entity;

/**
 *
 * @property integer $id
 * @property string $name
 * @property integer $position_id
 * @property integer $type
 * @property string $url
 * @property string $content
 * @property integer $start_at
 * @property integer $end_at
 * @property integer $status
 * @property integer $updated_at
 * @property integer $created_at
 */
class AdEntity extends Entity {
    public static function tableName(): string {
        return 'ad';
    }

    protected function rules(): array {
        return [
            'name' => 'required|string:0,30',
            'position_id' => 'required|int',
            'type' => 'int:0,127',
            'url' => 'required|string:0,255',
            'content' => 'required|string:0,255',
            'start_at' => 'int',
            'end_at' => 'int',
            'status' => 'int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => '广告名',
            'position_id' => '广告位',
            'type' => '类型',
            'url' => '链接',
            'content' => '内容',
            'start_at' => '开始时间',
            'end_at' => '结束时间',
            'status' => '状态',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


}