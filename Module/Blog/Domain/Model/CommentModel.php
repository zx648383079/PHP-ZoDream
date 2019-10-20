<?php
namespace Module\Blog\Domain\Model;

use Domain\Model\Model;
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
 * @property integer $type 评论类型(pingback/普通)
 * @property integer $karma
 * @property integer $parent_id
 * @property integer $user_id
 * @property integer $blog_id
 * @property integer $agree
 * @property integer $disagree
 * @property integer $position
 */
class CommentModel extends CommentEntity {

    protected $hidden = ['email', 'url', 'ip', 'agent'];

    public function replies() {
	    return $this->hasMany(static::class, 'parent_id');
    }

    public function blog() {
	    return $this->hasOne(BlogSimpleModel::class, 'id', 'blog_id');
    }

    public function getReplyCount() {
	    return $this->reply_count = static::where('parent_id', $this->id)->count();
    }

    public static function getChildren($postId, $parentId = 0) {
	    $data = static::find()->alias('c')->left('user u', ['u.id' => 'c.user_id'])
            ->where(['c.post_id' => $postId, 'c.parent_id' => $parentId])
            ->orderBy('c.create_at desc')->select([

            ])->asArray()->all();
	    foreach ($data as &$item) {
	        $item['children'] = static::getChildren($postId, $item['id']);
        }
        return $data;
    }

    public static function getAll($postId) {
        $data = static::find()->alias('c')->left('user u', ['u.id' => 'c.user_id'])
            ->where(['c.post_id' => $postId])
            ->orderBy('c.parent_id asc,c.create_at desc')->select([

            ])->asArray()->all();
        return static::getChildrenByData(0, $data);
    }

    protected static function getChildrenByData($parentId, array $args) {
	    $data = [];
	    foreach ($args as $item) {
	        if ($item['parent_id'] == $parentId) {
	            $item['children'] = static::getChildrenByData($item['id'], $args);
	            $data[] = $item;
            }
        }
        return $data;
    }

    public static function getHots() {
	    return static::find()->alias('c')->left('posts p', ['p.id' => 'c.post_id'])
            ->orderBy('c.agree desc')->select('c.id, c.name, c.content, c.agree, c.create_at, c.post_id, p.title')
            ->limit(5)->asArray()->all();
    }

    public static function getNew() {
        return static::find()->alias('c')->left('posts p', ['p.id' => 'c.post_id'])
            ->orderBy('c.create_at desc')->select('c.id, c.name, c.content, c.agree, c.create_at, c.post_id, p.title')
            ->limit(5)->asArray()->all();
    }

    public static function canAgree($id) {
	    return BlogLogModel::where([
	        'user_id' => auth()->id(),
            'type' => BlogLogModel::TYPE_COMMENT,
            'id_value' => $id,
            'action' => ['in', [BlogLogModel::ACTION_AGREE, BlogLogModel::ACTION_DISAGREE]]
        ])->count() < 1;
    }

    /**
     * 是否赞同此评论
     * @param bool $isAgree
     * @return bool
     * @throws \Exception
     */
    public function agreeThis($isAgree = true) {
        if ($isAgree) {
            $this->agree ++;
        } else {
            $this->disagree ++;
        }
        if (!$this->save()) {
            return false;
        }
        return !!BlogLogModel::create([
            'type' => BlogLogModel::TYPE_COMMENT,
            'action' => $isAgree ? BlogLogModel::ACTION_AGREE : BlogLogModel::ACTION_DISAGREE,
            'id_value' => $this->id,
            'user_id' => auth()->id()
        ]);
    }
}