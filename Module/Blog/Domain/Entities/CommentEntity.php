<?php
namespace Module\Blog\Domain\Entities;

use Domain\Concerns\ExtraRule;
use Domain\Entities\Entity;

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
 * @property string $agent 评论者的USER AGENT
 * @property string $extra_rule
 * @property integer $parent_id
 * @property integer $position
 * @property integer $user_id
 * @property integer $blog_id
 * @property integer $agree_count
 * @property integer $disagree_count
 */
class CommentEntity extends Entity {

    use ExtraRule;

	public static function tableName() {
        return 'blog_comment';
    }

    public function rules() {
        return [
            'content' => 'required|string:0,255',
            'extra_rule' => 'string:0,255',
            'name' => 'string:0,30',
            'email' => 'string:0,50',
            'url' => 'string:0,50',
            'parent_id' => 'int',
            'position' => 'int',
            'user_id' => 'int',
            'blog_id' => 'required|int',
            'ip' => 'string:0,120',
            'agent' => 'string:0,255',
            'agree_count' => 'int',
            'disagree_count' => 'int',
            'approved' => 'int:0,127',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'content' => '内容',
            'name' => 'Name',
            'email' => 'Email',
            'url' => 'Url',
            'ip' => 'Ip',
            'created_at' => '发布时间',
            'karma' => 'Karma',
            'approved' => 'Approved',
            'agent' => 'Agent',
            'type' => 'Type',
            'parent_id' => 'Parent Id',
            'user_id' => 'User Id',
            'blog_id' => 'Blog Id',
            'agree_count' => 'Agree',
            'disagree_count' => 'Disagree',
            'position' => 'Position',
        ];
    }
}