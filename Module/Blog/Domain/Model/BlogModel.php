<?php
namespace Module\Blog\Domain\Model;

use Domain\Model\Model;
use Module\Auth\Domain\Model\UserModel;
use Zodream\Database\Query\Query;
use Zodream\Helpers\Time;


/**
* Class BlogModel
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $keywords
 * @property string $thumb
 * @property integer $edit_type
 * @property string $content
 * @property integer $user_id
 * @property integer $term_id
 * @property integer $type
 * @property string $source_url
 * @property integer $recommend
 * @property integer $comment_count
 * @property integer $click_count
 * @property integer $comment_status
 * @property integer $deleted_at
 * @property integer $created_at
 * @property integer $updated_at
*/
class BlogModel extends Model {

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

	public function term() {
	    return $this->hasOne(TermModel::class, 'id', 'term_id');
    }

    public function user() {
        return $this->hasOne(UserModel::class, 'id', 'user_id');
    }

    public function comment() {
	    return $this->hasMany(CommentModel::class, 'blog_id', 'id');
    }

	public function getUrlAttribute() {
	    return url('./', ['id' => $this->id]);
    }

	public function getPreviousAttribute() {
	    return static::where('id', '<', $this->id)->orderBy('id desc')->select('id, title, description, created_at')->one();
    }

    public function getNextAttribute() {
	    return static::where('id', '>', $this->id)->orderBy('id asc')->select('id, title, description, created_at')->one();
    }

    /**
     * @return string|void
     */
    public function getCreatedAtAttribute() {
        return Time::isTimeAgo($this->getAttributeValue('created_at'), 2678400);
    }

    public static function getNew() {
	    return static::orderBy('create_at desc')->select('id, title, description, created_at')->limit(5)->all();
    }

    public static function getHot() {
        return static::orderBy('comment_count desc')->select('id, title, description, created_at')->limit(5)->all();
    }

    public static function getBest() {
        return static::orderBy('recommend desc')
            ->select('id, title, description, created_at')
            ->limit(5)->all();
    }

    public function getHotComment() {
	    return CommentModel::find()
            ->where(['blog_id' => $this->id])
            ->orderBy('created_at desc')->limit(5)->all();
    }

    /**
     * 是否允许评论
     * @param $id
     * @return bool
     */
    public static function canComment($id) {
        return static::where('comment_status', 1)
                ->where('id', $id)->count() > 0;
    }

    /**
     * 是否能推荐
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public static function canRecommend($id) {
        return BlogLogModel::where([
                'user_id' => auth()->id(),
                'type' => BlogLogModel::TYPE_BLOG,
                'id_value' => $id,
                'action' => BlogLogModel::ACTION_RECOMMEND
            ])->count() < 1;
    }

    /**
     * 推荐
     * @return bool
     * @throws \Exception
     */
    public function recommendThis() {
        $this->recommend++;
        if (!$this->save()) {
            return false;
        }
        return !!BlogLogModel::create([
            'type' => BlogLogModel::TYPE_BLOG,
            'action' => BlogLogModel::ACTION_RECOMMEND,
            'id_value' => $this->id,
            'user_id' => auth()->id()
        ]);
    }
}