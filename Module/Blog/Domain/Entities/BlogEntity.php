<?php
namespace Module\Blog\Domain\Entities;

use Domain\Entities\Entity;

/**
 * Class BlogEntity
 * @package Module\Blog\Domain\Entities
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $keywords
 * @property integer $parent_id
 * @property string $programming_language
 * @property string $language
 * @property string $thumb
 * @property integer $edit_type
 * @property string $content
 * @property integer $user_id
 * @property integer $term_id
 * @property integer $type
 * @property integer $recommend_count
 * @property integer $comment_count
 * @property integer $click_count
 * @property integer $open_type
 * @property string $open_rule
 * @property integer $deleted_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class BlogEntity extends Entity {

    const TYPE_ORIGINAL = 0; // 原创
    const TYPE_REPRINT = 1; // 转载

    const EDIT_HTML = 0;
    const EDIT_MARKDOWN = 1; // markdown

    const OPEN_PUBLIC = 0;
    const OPEN_LOGIN = 1;
    const OPEN_PASSWORD = 5;
    const OPEN_BUY = 6;
    const OPEN_DRAFT = 2;

    public static function tableName() {
        return 'blog';
    }

    public function rules() {
        return [
            'title' => 'required|string:0,200',
            'description' => 'string:0,255',
            'keywords' => 'string:0,255',
            'parent_id' => 'int',
            'programming_language' => 'string:0,20',
            'language' => '',
            'thumb' => 'string:0,255',
            'edit_type' => 'int:0,127',
            'content' => '',
            'user_id' => 'int',
            'term_id' => 'int',
            'type' => 'int:0,127',
            'recommend_count' => 'int',
            'comment_count' => 'int',
            'click_count' => 'int',
            'open_type' => 'int:0,127',
            'open_rule' => 'string:0,20',
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
            'audio_url' => '音频',
            'video_url' => '视频',
            'open_type' => '公开类型',
            'open_rule' => '公开规则',
            'weather' => '天气',
            'recommend_count' => '推荐',
            'comment_count' => '评论',
            'click_count' => '点击',
            'comment_status' => '评论状态',
            'deleted_at' => '删除时间',
            'created_at' => '发布时间',
            'updated_at' => '更新时间',
        ];
    }
}