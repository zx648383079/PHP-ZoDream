<?php
namespace Module\Blog\Domain\Model;

use Domain\Model\Model;


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
class CommentModel extends Model {

	public static function tableName() {
        return 'blog_comment';
    }

    protected function rules() {
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
            'agree' => 'int',
            'disagree' => 'int',
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
            'agree' => 'Agree',
            'disagree' => 'Disagree',
            'position' => 'Position',
        ];
    }

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