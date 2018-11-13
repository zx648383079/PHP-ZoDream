<?php
namespace Module\Shop\Domain\Model;

use Domain\Model\Model;

/**
 * 品牌
 * @package Module\Shop\Domain\Model
 * @property integer $id
 * @property string $name
 * @property string $keywords
 * @property string $description
 * @property string $logo
 * @property string $app_logo
 * @property string $url
 */
class BrandModel extends Model {
    public static function tableName() {
        return 'shop_brand';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,100',
            'keywords' => 'string:0,200',
            'description' => 'string:0,200',
            'logo' => 'string:0,200',
            'app_logo' => 'string:0,200',
            'url' => 'string:0,200',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => '名称',
            'keywords' => '关键字',
            'description' => '简介',
            'logo' => 'Logo',
            'app_logo' => 'App Logo',
            'url' => '官网',
        ];
    }
}