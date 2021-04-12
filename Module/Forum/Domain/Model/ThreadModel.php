<?php
namespace Module\Forum\Domain\Model;

use Domain\Model\Model;
use Module\Auth\Domain\Model\UserSimpleModel;

/**
* Class ThreadModel
 * @property integer $id
 * @property integer $forum_id
 * @property integer $classify_id
 * @property string $title
 * @property integer $user_id
 * @property integer $view_count
 * @property integer $post_count
 * @property integer $collect_count
 * @property integer $is_highlight
 * @property integer $is_digest
 * @property integer $is_closed
 * @property integer $top_type
 * @property integer $is_private_post
 * @property integer $created_at
 * @property integer $updated_at
*/
class ThreadModel extends Model {

    protected array $append = ['user'];

	public static function tableName() {
        return 'bbs_thread';
    }

    protected function rules() {
        return [
            'forum_id' => 'required|int',
            'classify_id' => 'int',
            'title' => 'required|string:0,200',
            'user_id' => 'required|int',
            'view_count' => 'int',
            'post_count' => 'int',
            'collect_count' => 'int',
            'is_highlight' => 'int:0,9',
            'is_digest' => 'int:0,9',
            'is_closed' => 'int:0,9',
            'is_private_post' => 'int:0,9',
            'top_type' => 'int:0,127',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'forum_id' => 'Forum Id',
            'classify_id' => 'Classify Id',
            'title' => 'Title',
            'user_id' => 'User Id',
            'view_count' => 'View Count',
            'post_count' => 'Post Count',
            'collect_count' => 'Collect Count',
            'is_highlight' => '是否高亮',
            'is_digest' => '是否精华',
            'is_closed' => '是否关闭',
            'is_private_post' => '是否仅楼主可见',
            'top_type' => '置顶',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function forum() {
	    return $this->hasOne(ForumModel::class, 'id', 'forum_id');
    }

    public function classify() {
        return $this->hasOne(ForumClassifyModel::class, 'id', 'classify_id');
    }

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

}