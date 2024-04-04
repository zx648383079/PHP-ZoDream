<?php
namespace Module\Blog\Domain\Model;

/**
* Class BlogModel
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $keywords
 * @property string $thumb
 * @property string $language
 * @property string $programming_language
 * @property integer $parent_id
 * @property integer $edit_type
 * @property string $content
 * @property integer $user_id
 * @property integer $term_id
 * @property integer $type
 * @property integer $open_type
 * @property string $open_rule
 * @property integer $recommend_count
 * @property integer $comment_count
 * @property integer $click_count
 * @property integer $publish_status
 * @property integer $created_at
 * @property integer $updated_at
*/
class BlogPageModel extends BlogModel {

    const SIMPLE_MODE = ['id', 'title', 'description', 'user_id', 'type',
        'thumb',
        'language',
        'programming_language',
        'term_id',
        'parent_id',
        'open_type',
        'comment_count',
        'publish_status',
        'click_count', 'recommend_count', 'created_at'];

    protected array $append = ['url', 'term', 'user'];

    public static function query() {
        return parent::query()->select(self::SIMPLE_MODE);
    }

}