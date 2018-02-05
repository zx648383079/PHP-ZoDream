<?php
namespace Module\Blog\Domain\Model;

use Domain\Model\Model;
use Zodream\Database\Query\Query;
use Zodream\Domain\Access\Auth;
use Zodream\Infrastructure\Http\Request;
use Zodream\Service\Routing\Url;

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
 * @property integer $recommend
 * @property integer $comment_status
 * @property integer $comment_count
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
            'title' => 'required|string:3-200',
            'description' => 'string:3-255',
            'keywords' => 'string:3-255',
            'thumb' => 'string:3-255',
            'content' => '',
            'user_id' => 'int',
            'term_id' => 'int',
            'recommend' => 'int',
            'comment_status' => 'int:0-1',
            'comment_count' => 'int',
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
            'recommend' => 'Recommend',
            'comment_status' => 'Comment Status',
            'comment_count' => 'Comment Count',
            'deleted_at' => 'Deleted At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

	public function term() {
	    return $this->hasOne(TermModel::class, 'id', 'term_id');
    }

	public function getUrlAttribute() {
	    return Url::to('./home/detail', ['id' => $this->id]);
    }

	public function getPreviousAttribute() {
	    return static::where('id', '<', $this->id)->order('id desc')->select('id, title, description, created_at')->one();
    }

    public function getNextAttribute() {
	    return static::where('id', '>', $this->id)->order('id asc')->select('id, title, description, created_at')->one();
    }

	public static function getNew() {
	    return static::order('create_at desc')->select('id, title, description, created_at')->limit(5)->all();
    }

    public static function getHot() {
        return static::order('comment_count desc')->select('id, title, description, created_at')->limit(5)->all();
    }

    public static function getBest() {
        return static::order('recommend desc')
            ->select('id, title, description, created_at')
            ->limit(5)->all();
    }

    public function getHotComment() {
	    return CommentModel::find()
            ->where(['blog_id' => $this->id])
            ->order('created_at desc')->limit(5)->all();
    }

    public static function canComment($id) {
        return static::where([
                'comment_status' => 0,
                'id' => $id,
            ])->count() > 0;
    }

    public static function canRecommend($id) {
        return BlogLogModel::where([
                'user_id' => Auth::id(),
                'type' => BlogLogModel::TYPE_BLOG,
                'id_value' => $id,
                'action' => BlogLogModel::ACTION_RECOMMEND
            ])->count() < 1;
    }
}