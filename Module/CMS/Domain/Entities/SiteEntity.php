<?php
namespace Module\CMS\Domain\Entities;


use Domain\Entities\Entity;

/**
 * @property integer $id
 * @property string $title
 * @property string $keywords
 * @property string $description
 * @property string $logo
 * @property string $theme
 * @property string $match_rule
 * @property integer $is_default
 * @property integer $status
 * @property string $language
 * @property string $options
 * @property integer $created_at
 * @property integer $updated_at
 */
class SiteEntity extends Entity {
    public static function tableName(): string {
        return 'cms_site';
    }

    protected function rules(): array {
        return [
            'title' => 'required|string:0,255',
            'keywords' => 'string:0,255',
            'description' => 'string:0,255',
            'logo' => 'string:0,255',
            'theme' => 'required|string:0,100',
            'match_rule' => 'string:0,100',
            'is_default' => 'int:0,127',
            'status' => 'int:0,127',
            'language' => 'string:0,10',
            'options' => '',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'title' => '站点标题',
            'keywords' => '关键词',
            'description' => '简介',
            'logo' => 'Logo',
            'theme' => '主题',
            'match_rule' => '匹配规则',
            'is_default' => '是否为默认站点',
            'status' => '状态',
            'language' => '本地化语言',
            'options' => 'Options',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}