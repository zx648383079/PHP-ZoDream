<?php
declare(strict_types=1);
namespace Module\Plugin\Domain\Entities;


use Domain\Entities\Entity;

/**
 *
 * @property integer $id
 * @property integer $plugin_id
 * @property integer $status
 * @property string $content
 * @property integer $created_at
 */
class PluginLogEntity extends Entity {

    public static function tableName(): string {
        return 'plugin_log';
    }

    protected function rules() {
        return [
            'plugin_id' => 'required|int',
            'status' => 'int:0,127',
            'content' => '',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'plugin_id' => 'Plugin Id',
            'status' => 'Status',
            'content' => 'Content',
            'created_at' => 'Created At',
        ];
    }
}