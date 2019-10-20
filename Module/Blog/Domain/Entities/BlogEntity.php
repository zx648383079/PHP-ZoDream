<?php
namespace Module\Blog\Domain\Entities;

use Domain\Entities\Entity;

class BlogEntity extends Entity {

    const TYPE_ORIGINAL = 0; // 原创
    const TYPE_REPRINT = 1; // 转载

    const EDIT_HTML = 0;
    const EDIT_MARKDOWN = 1; // markdown

    public static function tableName() {
        return 'blog';
    }

    protected function rules() {
        return [
            'title' => 'required|string:0,200',
            'description' => 'string:0,255',
            'keywords' => 'string:0,255',
            'thumb' => 'string:0,255',
            'language' => 'in:zh,en',
            'programming_language' => 'string:0,20',
            'parent_id' => 'int',
            'edit_type' => 'int:0,9',
            'content' => '',
            'user_id' => 'int',
            'term_id' => 'int',
            'type' => 'int:0,9',
            'source_url' => 'string:0,100',
            'recommend' => 'int',
            'comment_count' => 'int',
            'click_count' => 'int',
            'comment_status' => 'int:0,9',
            'deleted_at' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'title' => '标题',
            'description' => '说明',
            'keywords' => '关键字',
            'thumb' => '图片',
            'parent_id' => '主语言',
            'language' => '语言',
            'programming_language' => '编程语言',
            'edit_type' => '编辑器',
            'content' => '内容',
            'user_id' => 'User Id',
            'term_id' => '分类',
            'type' => '类型',
            'source_url' => '来源',
            'recommend' => '推荐',
            'comment_count' => '评论',
            'click_count' => '点击',
            'comment_status' => '评论状态',
            'deleted_at' => '删除时间',
            'created_at' => '发布时间',
            'updated_at' => '更新时间',
        ];
    }
}