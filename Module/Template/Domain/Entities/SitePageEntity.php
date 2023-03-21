<?php
declare(strict_types=1);
namespace Module\Template\Domain\Entities;

use Domain\Entities\Entity;

/**
 *
 * @property integer $id
 * @property integer $site_id
 * @property integer $component_id
 * @property integer $type
 * @property string $name
 * @property string $title
 * @property string $keywords
 * @property string $thumb
 * @property string $description
 * @property string $settings
 * @property integer $position
 * @property string $dependencies
 * @property integer $status
 * @property integer $updated_at
 * @property integer $created_at
 */
class SitePageEntity extends Entity {
    public static function tableName() {
        return 'tpl_site_page';
    }

    protected function rules() {
        return [
            'site_id' => 'required|int',
            'component_id' => 'required|int',
            'type' => 'int:0,127',
            'name' => 'required|string:0,100',
            'title' => 'string:0,200',
            'keywords' => 'string:0,255',
            'thumb' => 'string:0,255',
            'description' => 'string:0,255',
            'settings' => '',
            'position' => 'int:0,127',
            'dependencies' => 'string:0,255',
            'status' => 'int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'site_id' => 'Site Id',
            'component_id' => 'Component Id',
            'type' => 'Type',
            'name' => 'Name',
            'title' => 'Title',
            'keywords' => 'Keywords',
            'thumb' => 'Thumb',
            'description' => 'Description',
            'settings' => 'Settings',
            'position' => 'Position',
            'dependencies' => 'Dependencies',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}