<?php
declare(strict_types=1);
namespace Module\Plugin\Domain\Entities;

use Domain\Entities\Entity;

/**
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $author
 * @property string $version
 * @property string $path
 * @property integer $status
 * @property string $configs
 * @property integer $updated_at
 * @property integer $created_at
 */
class PluginEntity extends Entity {

    public static function tableName(): string {
        return 'plugin';
    }

    protected function rules(): array {
        return [
            'name' => 'required|string:0,255',
            'description' => 'string:0,255',
            'author' => 'string:0,255',
            'version' => 'string:0,255',
            'path' => 'required|string:0,255',
            'status' => 'int:0,127',
            'configs' => '',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'description' => 'Description',
            'author' => 'Author',
            'version' => 'Version',
            'path' => 'Path',
            'status' => 'Status',
            'configs' => 'Configs',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}