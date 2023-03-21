<?php
declare(strict_types=1);
namespace Module\Template\Domain\Entities;

use Domain\Entities\Entity;

/**
 * @property integer $id
 * @property string $name
 * @property integer $user_id
 * @property string $title
 * @property string $keywords
 * @property string $thumb
 * @property string $logo
 * @property string $description
 * @property string $domain
 * @property integer $default_page_id
 * @property integer $is_share
 * @property integer $share_price
 * @property integer $status
 * @property integer $updated_at
 * @property integer $created_at
 */
class SiteEntity extends Entity {
    public static function tableName() {
        return 'tpl_site';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,100',
            'user_id' => 'required|int',
            'title' => 'string:0,200',
            'keywords' => 'string:0,255',
            'thumb' => 'string:0,255',
            'logo' => 'string:0,255',
            'description' => 'string:0,255',
            'domain' => 'string:0,50',
            'default_page_id' => 'int',
            'is_share' => 'int:0,127',
            'share_price' => 'int',
            'status' => 'int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'user_id' => 'User Id',
            'title' => 'Title',
            'keywords' => 'Keywords',
            'thumb' => 'Thumb',
            'description' => 'Description',
            'domain' => 'Domain',
            'default_page_id' => 'Default Page Id',
            'is_share' => 'Is Share',
            'share_price' => 'Share Price',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}