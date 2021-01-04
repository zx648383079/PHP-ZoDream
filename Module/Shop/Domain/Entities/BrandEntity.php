<?php
namespace Module\Shop\Domain\Entities;

use Domain\Entities\Entity;

class BrandEntity extends Entity {
    public static function tableName() {
        return 'shop_brand';
    }

    public function rules() {
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