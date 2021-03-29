<?php
namespace Module\MicroBlog\Domain\Model;

use Domain\Model\Model;

/**
 * Class BlogTopicModel
 * @package Module\MicroBlog\Domain\Model
 * @property integer $id
 * @property integer $micro_id
 * @property integer $topic_id
 */
class BlogTopicModel extends Model {

	public static function tableName() {
        return 'micro_blog_topic';
    }

    protected function rules() {
        return [
            'micro_id' => 'required|int',
            'topic_id' => 'required|int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'micro_id' => 'Micro Id',
            'topic_id' => 'Topic Id',
        ];
    }

}