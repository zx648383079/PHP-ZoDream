<?php
namespace Module\Blog\Domain\Model;

use Domain\Model\Model;
use Module\Auth\Domain\Model\UserModel;
use Zodream\Database\Query\Query;




/**
* Class BlogModel
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $keywords
 * @property string $thumb
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
	public static function tableName() {
        return 'blog';
    }

    protected function rules() {
        return [
            'title' => 'required|string:0,200',
            'description' => 'string:0,255',
            'keywords' => 'string:0,255',
            'thumb' => 'string:0,255',
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
            'title' => 'Title',
            'description' => 'Description',
            'keywords' => 'Keywords',
            'thumb' => 'Thumb',
            'content' => 'Content',
            'user_id' => 'User Id',
            'term_id' => 'Term Id',
            'type' => 'Type',
            'source_url' => 'Source Url',
            'recommend' => 'Recommend',
            'comment_count' => 'Comment Count',
            'click_count' => 'Click Count',
            'comment_status' => 'Comment Status',
            'deleted_at' => 'Deleted At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
	    return url('./detail', ['id' => $this->id]);
    }

	public function getPreviousAttribute() {
	    return static::where('id', '<', $this->id)->orderBy('id desc')->select('id, title, description, created_at')->one();
    }

    public function getNextAttribute() {
	    return static::where('id', '>', $this->id)->orderBy('id asc')->select('id, title, description, created_at')->one();
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

    public static function canComment($id) {
        return static::where([
                'comment_status' => 0,
                'id' => $id,
            ])->count() > 0;
    }

    public static function canRecommend($id) {
        return BlogLogModel::where([
                'user_id' => auth()->id(),
                'type' => BlogLogModel::TYPE_BLOG,
                'id_value' => $id,
                'action' => BlogLogModel::ACTION_RECOMMEND
            ])->count() < 1;
    }
}