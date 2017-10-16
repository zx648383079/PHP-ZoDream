<?php
namespace Module\Blog\Domain\Model;

use Domain\Model\Model;
use Zodream\Service\Routing\Url;

/**
* Class PostModel
* @property integer $id
* @property string $title
* @property string $content
 * @property integer $term_id
* @property integer $user_id
* @property integer $updated_at
* @property integer $created_at
* @property string $description
* @property string $status
* @property string $comment_status
* @property integer $position
* @property string $type
* @property integer $comment_count
* @property integer $recommend
*/
class BlogModel extends Model {
	public static function tableName() {
        return 'blog';
    }

    protected function rules() {
		return array (
            'title' => 'required|string:3-255',
            'content' => 'required',
            'user_id' => 'required|int',
            'term_id' => 'required|int',
            'updated_at' => '|int',
            'created_at' => '|int',
            'description' => 'string:0-255',
            'status' => '|string:3-20',
            'comment_status' => '|string:3-20',
            'position' => '|int',
            'comment_count' => '|int',
            'recommend' => '|int',
		);
	}

	protected function labels() {
		return array (
		  'id' => 'Id',
		  'title' => 'Title',
		  'content' => 'Content',
		  'user_id' => 'User Id',
		  'updated_at' => 'Update At',
		  'created_at' => 'Create At',
		  'description' => '节选',
		  'status' => 'Status',
		  'comment_status' => 'Comment Status',
		  'ping_status' => 'Ping Status',
		  'password' => 'Password',
		  'name' => 'Name',
		  'to_ping' => 'To Ping',
		  'pinged' => 'Pinged',
		  'parent' => 'Parent',
		  'guid' => 'Guid',
		  'position' => 'Position',
		  'type' => 'Type',
		  'mime_type' => 'Mime Type',
		  'comment_count' => 'Comment Count',
		  'recommend' => 'Recommend',
		);
	}

	public function getUrlAttribute() {
	    return Url::to('blog/home/detail', ['id' => $this->id]);
    }

	public function getPreviousAttribute() {
	    return static::where('id', '<', $this->id)
            ->whereIn('status', array(
                'publish',
                'password'
            ))->order('id desc')->select('id, title, description, created_at')->one();
    }

    public function getNextAttribute() {
	    return static::where('id', '>', $this->id)
            ->whereIn('status', array(
                'publish',
                'password'
            ))->order('id asc')->select('id, title, description, created_at')->one();
    }

	public static function getNew() {
	    return static::whereIn('status', array(
            'publish',
            'password'
        ))->order('create_at desc')->select('id, title, description, created_at')->limit(5)->all();
    }

    public static function getHot() {
        return static::whereIn('status', array(
            'publish',
            'password'
        ))->order('comment_count desc')->select('id, title, description, created_at')->limit(5)->all();
    }

    public static function getBest() {
        return static::whereIn('status', array(
            'publish',
            'password'
        ))->order('recommend desc')
            ->select('id, title, description, created_at')
            ->limit(5)->all();
    }

    public function getHotComment() {
	    return CommentModel::find()
            ->where(['post_id' => $this->id])
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