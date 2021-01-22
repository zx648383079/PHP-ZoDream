<?php
namespace Module\Video\Domain\Models;

use Domain\Model\Model;

/**
 * 短视频
 * @package Module\Video\Domain\Models
 * @property integer $id
 * @property string $content
 * @property integer $parent_id
 * @property integer $user_id
 * @property integer $video_id
 * @property integer $agree
 * @property integer $disagree
 * @property integer $created_at
 */
class CommentModel extends Model {
    public static function tableName() {
        return 'video_comment';
    }

    protected function rules() {
        return [
            'content' => 'required|string:0,255',
            'parent_id' => 'int',
            'user_id' => 'int',
            'video_id' => 'required|int',
            'agree' => 'int',
            'disagree' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'content' => 'Content',
            'parent_id' => 'Parent Id',
            'user_id' => 'User Id',
            'video_id' => 'Video Id',
            'agree' => 'Agree',
            'disagree' => 'Disagree',
            'created_at' => 'Created At',
        ];
    }

}