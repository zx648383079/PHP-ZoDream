<?php
namespace Module\Shop\Domain\Entities;

use Domain\Entities\Entity;

class ArticleEntity extends Entity {
    public static function tableName() {
        return 'shop_article';
    }

    protected function rules() {
        return [
            'cat_id' => 'required|int',
            'title' => 'required|string:0,100',
            'keywords' => 'string:0,200',
            'thumb' => 'string:0,200',
            'description' => 'string:0,200',
            'brief' => 'string:0,200',
            'url' => 'string:0,200',
            'file' => 'string:0,200',
            'content' => 'required',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'cat_id' => '分类',
            'title' => '标题',
            'keywords' => '关键字',
            'thumb' => '主图',
            'description' => '说明',
            'brief' => '简介',
            'url' => '链接',
            'file' => '附件',
            'content' => '内容',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}