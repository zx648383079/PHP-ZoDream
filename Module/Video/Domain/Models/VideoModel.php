<?php
namespace Module\Video\Domain\Models;

use Domain\Model\Model;
use Module\Auth\Domain\Model\UserSimpleModel;
use Module\Video\Domain\Repositories\VideoRepository;

/**
 * 短视频
 * @package Module\Video\Domain\Models
 * @property integer $id
 * @property integer $user_id
 * @property string $cover
 * @property string $content
 * @property string $video_path
 * @property integer $video_duration
 * @property integer $video_height
 * @property integer $video_width
 * @property integer $music_id
 * @property integer $music_offset
 * @property integer $like_count
 * @property integer $comment_count
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class VideoModel extends Model {

    protected array $append = ['is_liked'];

    public static function tableName() {
        return 'video_video';
    }

    protected function rules() {
        return [
            'user_id' => 'required|int',
            'cover' => 'string:0,255',
            'content' => 'string:0,255',
            'video_path' => 'required|string:0,255',
            'video_duration' => 'int:0,9999',
            'video_height' => 'int:0,9999',
            'video_width' => 'int:0,9999',
            'music_id' => 'required|int',
            'music_offset' => 'int:0,999',
            'like_count' => 'int',
            'comment_count' => 'int',
            'status' => 'int:0,127',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'cover' => 'Cover',
            'content' => 'Content',
            'video_path' => 'Video Path',
            'video_duration' => 'Video Duration',
            'video_height' => 'Video Height',
            'video_width' => 'Video Width',
            'music_id' => 'Music Id',
            'music_offset' => 'Music Offset',
            'like_count' => 'Like Count',
            'comment_count' => 'Comment Count',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function music() {
        return $this->hasOne(MusicModel::class, 'id', 'music_id');
    }

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

    public function getIsLikedAttribute() {
        return VideoRepository::isLiked($this->id);
    }
}