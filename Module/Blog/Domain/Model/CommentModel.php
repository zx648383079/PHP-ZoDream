<?php
namespace Module\Blog\Domain\Model;

use Module\Blog\Domain\Entities\CommentEntity;


/**
 * Class CommentModel
 * @property integer $id
 * @property string $content 评论正文
 * @property string $name 评论者
 * @property string $email 评论者邮箱
 * @property string $url 评论者网址
 * @property string $ip 评论者IP
 * @property integer $created_at 评论时间
 * @property integer $approved 评论是否被批准
 * @property integer $agent 评论者的USER AGENT
 * @property string $extra_rule
 * @property integer $parent_id
 * @property integer $user_id
 * @property integer $blog_id
 * @property integer $agree_count
 * @property integer $disagree_count
 * @property integer $position
 * @property integer $agree_type {0:无，1:同意 2:不同意}
 */
class CommentModel extends CommentEntity {

    protected array $hidden = ['email', 'url', 'ip', 'agent'];

    public function replies() {
	    return $this->hasMany(static::class, 'parent_id')
            ->where('approved', 1);
    }

    public function blog() {
	    return $this->hasOne(BlogSimpleModel::class, 'id', 'blog_id');
    }

    public function getReplyCount() {
	    return $this->reply_count = static::where('parent_id', $this->id)->count();
    }

}