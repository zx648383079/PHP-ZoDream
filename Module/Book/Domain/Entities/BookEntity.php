<?php
namespace Module\Book\Domain\Entities;

use Domain\Entities\Entity;
/**
 * @property integer $id
 * @property string $name
 * @property string $cover 封面
 * @property string $description 简介
 * @property integer $words_count 字数
 * @property integer $author_id 作者
 * @property integer $user_id
 * @property integer $classify
 * @property integer $cat_id
 * @property integer $size
 * @property string $source
 * @property integer $click_count
 * @property integer $recommend_count
 * @property integer $over_at
 * @property integer $deleted_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class BookEntity extends Entity {
    public static function tableName() {
        return 'book';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,100',
            'cover' => 'string:0,200',
            'description' => 'string:0,200',
            'author_id' => 'int',
            'user_id' => 'int',
            'classify' => 'int',
            'cat_id' => 'int:0,999',
            'size' => 'int',
            'source' => 'string:0,200',
            'click_count' => 'int',
            'recommend_count' => 'int',
            'over_at' => 'int',
            'deleted_at' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => '书名',
            'cover' => '封面',
            'description' => '简介',
            'author_id' => '作者',
            'user_id' => 'User Id',
            'classify' => '分级',
            'cat_id' => '分类',
            'size' => '字数',
            'source' => '来源',
            'click_count' => '点击',
            'recommend_count' => '推荐',
            'over_at' => '完本时间',
            'deleted_at' => '删除时间',
            'created_at' => '发布时间',
            'updated_at' => '更新时间',
        ];
    }
}