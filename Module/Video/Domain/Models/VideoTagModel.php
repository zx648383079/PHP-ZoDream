<?php
namespace Module\Video\Domain\Models;

use Domain\Model\Model;

/**
 * Class VideoTagModel
 * @package Module\Video\Domain\Models
 * @property integer $tag_id
 * @property integer $video_id
 */
class VideoTagModel extends Model {
    public static function tableName() {
        return 'video_tag_relationship';
    }

    protected $primaryKey = '';

    protected function rules() {
        return [
            'tag_id' => 'required|int',
            'video_id' => 'required|int',
        ];
    }

    protected function labels() {
        return [
            'tag_id' => 'Tag Id',
            'video_id' => 'Video Id',
        ];
    }
}