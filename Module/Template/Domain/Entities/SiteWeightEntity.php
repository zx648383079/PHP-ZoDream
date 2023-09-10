<?php
declare(strict_types=1);
namespace Module\Template\Domain\Entities;

use Domain\Entities\Entity;

/**
 *
 * @property integer $id
 * @property integer $site_id
 * @property integer $component_id
 * @property string $title
 * @property string $content
 * @property string $settings
 * @property integer $style_id
 * @property integer $is_share
 * @property integer $updated_at
 * @property integer $created_at
 */
class SiteWeightEntity extends Entity {
    public static function tableName(): string {
        return 'tpl_site_weight';
    }

    protected function rules(): array {
        return [
            'site_id' => 'required|int',
            'component_id' => 'required|int',
            'title' => 'string:0,200',
            'content' => '',
            'settings' => '',
            'style_id' => 'int',
            'is_share' => 'int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'site_id' => 'Site Id',
            'component_id' => 'Component Id',
            'title' => 'Title',
            'content' => 'Content',
            'settings' => 'Settings',
            'style_id' => 'Style Id',
            'is_share' => 'Is Share',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}