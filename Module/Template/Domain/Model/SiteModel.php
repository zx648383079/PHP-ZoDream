<?php
namespace Module\Template\Domain\Model;

use Domain\Model\Model;

/**
 * Class SiteModel
 * @package Module\Template\Domain\Model
 * @property integer $id
 * @property string $name
 * @property integer $user_id
 * @property string $title
 * @property string $keywords
 * @property string $thumb
 * @property string $description
 * @property string $domain
 * @property integer $theme_id
 * @property integer $default_page_id
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class SiteModel extends Model {
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
            'description' => 'string:0,255',
            'domain' => 'string:0,50',
            'theme_id' => 'required|int',
            'default_page_id' => 'int',
            'status' => 'int:0,127',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => '站点别名',
            'user_id' => 'User Id',
            'title' => '站点名',
            'keywords' => '关键字',
            'thumb' => '预览图',
            'description' => '简介',
            'domain' => '域名',
            'theme_id' => 'Theme Id',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}