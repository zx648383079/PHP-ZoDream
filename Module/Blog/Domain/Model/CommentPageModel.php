<?php
namespace Module\Blog\Domain\Model;

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
 * @property integer $type 评论类型(pingback/普通)
 * @property integer $karma
 * @property integer $parent_id
 * @property integer $user_id
 * @property integer $blog_id
 * @property integer $agree_count
 * @property integer $disagree_count
 * @property integer $position
 */
class CommentPageModel extends CommentModel {
    protected array $append = ['reply_count', 'replies'];
}