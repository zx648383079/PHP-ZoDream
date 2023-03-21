<?php
declare(strict_types=1);
namespace Module\Template\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 站点所需组件选取记录
 * @property integer $id
 * @property integer $component_id
 * @property integer $site_id
 * @property string $name
 * @property string $description
 * @property string $thumb
 * @property integer $type
 * @property string $author
 * @property string $version
 * @property string $path
 * @property integer $updated_at
 * @property integer $created_at
 */
class SiteComponentEntity extends Entity {
    public static function tableName() {
        return 'tpl_site_component';
    }

    protected function rules() {
        return [
            'component_id' => 'required|int',
            'site_id' => 'required|int',
            'name' => 'required|string:0,30',
            'description' => 'string:0,200',
            'thumb' => 'string:0,100',
            'type' => 'int:0,127',
            'author' => 'string:0,20',
            'version' => 'string:0,10',
            'path' => 'required|string:0,200',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'component_id' => 'Component Id',
            'site_id' => 'Site Id',
            'name' => 'Name',
            'description' => 'Description',
            'thumb' => 'Thumb',
            'type' => 'Type',
            'author' => 'Author',
            'version' => 'Version',
            'path' => 'Path',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}