<?php
namespace Module\Blog\Domain\Model;

use Domain\Model\Model;
use Zodream\Domain\Access\Auth;

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
 * @property integer $parent_id
 * @property integer $user_id
 * @property integer $post_id
 * @property integer $agree
 * @property integer $disagree
 */
class CommentModel extends Model {

	public static function tableName() {
        return 'comment';
    }

    protected function rules() {
        return [
            'content' => 'required',
            'name' => 'string:3-45',
            'email' => 'string:3-100',
            'url' => 'string:3-200',
            'ip' => 'string:3-20',
            'created_at' => 'int',
            'karma' => 'int',
            'approved' => 'string:3-20',
            'agent' => 'string:3-255',
            'type' => 'string:3-20',
            'parent_id' => 'int',
            'user_id' => 'int',
            'blog_id' => 'int',
            'agree' => 'int',
            'disagree' => 'int',
            'position' => 'int'
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'content' => 'Content',
            'name' => 'Name',
            'email' => 'Email',
            'url' => 'Url',
            'ip' => 'Ip',
            'created_at' => 'Created At',
            'karma' => 'Karma',
            'approved' => 'Approved',
            'agent' => 'Agent',
            'type' => 'Type',
            'parent_id' => 'Parent Id',
            'user_id' => 'User Id',
            'blog_id' => 'Blog Id',
            'agree' => 'Agree',
            'disagree' => 'Disagree',
        ];
    }

    public function replies() {
	    return $this->hasMany(static::class, 'parent_id');
    }

    public function getReplyCount() {
	    return $this->reply_count = static::where('parent_id', $this->id)->count();
    }

    public static function getChildren($postId, $parentId = 0) {
	    $data = static::find()->alias('c')->left('user u', ['u.id' => 'c.user_id'])
            ->where(['c.post_id' => $postId, 'c.parent_id' => $parentId])
            ->order('c.create_at desc')->select([

            ])->asArray()->all();
	    foreach ($data as &$item) {
	        $item['children'] = static::getChildren($postId, $item['id']);
        }
        return $data;
    }

    public static function getAll($postId) {
        $data = static::find()->alias('c')->left('user u', ['u.id' => 'c.user_id'])
            ->where(['c.post_id' => $postId])
            ->order('c.parent_id asc,c.create_at desc')->select([

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
            ->order('c.agree desc')->select('c.id, c.name, c.content, c.agree, c.create_at, c.post_id, p.title')
            ->limit(5)->asArray()->all();
    }

    public static function getNew() {
        return static::find()->alias('c')->left('posts p', ['p.id' => 'c.post_id'])
            ->order('c.create_at desc')->select('c.id, c.name, c.content, c.agree, c.create_at, c.post_id, p.title')
            ->limit(5)->asArray()->all();
    }

    public static function canAgree($id) {
	    return BlogLogModel::where([
	        'user_id' => Auth::id(),
            'type' => BlogLogModel::TYPE_COMMENT,
            'id_value' => $id,
            'action' => ['in', [BlogLogModel::ACTION_AGREE, BlogLogModel::ACTION_DISAGREE]]
        ])->count() < 1;
    }
}