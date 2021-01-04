<?php
namespace Module\Shop\Domain\Entities;

use Domain\Entities\Entity;

class CategoryEntity extends Entity {
    public static function tableName() {
        return 'shop_category';
    }

    public function rules() {
        return [
            'name' => 'required|string:0,100',
            'keywords' => 'string:0,200',
            'description' => 'string:0,200',
            'icon' => 'string:0,200',
            'parent_id' => 'int',
            'position' => 'int:0,999',
            'banner' => 'string:0,200',
            'app_banner' => 'string:0,200',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => '名称',
            'keywords' => '关键字',
            'description' => '简介',
            'icon' => '图标',
            'parent_id' => '上级',
            'position' => '排序',
            'banner' => 'Banner',
            'app_banner' => 'App Banner',
        ];
    }
}