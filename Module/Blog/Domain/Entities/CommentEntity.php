<?php
namespace Module\Blog\Domain\Entities;

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
class CommentEntity extends Entity {

	public static function tableName() {
        return 'blog_comment';
    }

    public function rules() {
        return [
            'content' => 'required',
            'name' => 'string:0,45',
            'email' => 'string:0,100',
            'url' => 'string:0,200',
            'ip' => 'string:0,20',
            'created_at' => 'int',
            'karma' => 'int',
            'approved' => 'string:0,20',
            'agent' => 'string:0,255',
            'type' => 'string:0,20',
            'parent_id' => 'int',
            'user_id' => 'int',
            'blog_id' => 'int',
            'agree_count' => 'int',
            'disagree_count' => 'int',
            'position' => 'int',
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