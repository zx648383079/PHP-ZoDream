<?php
namespace Module\MicroBlog\Domain\Model;

use Domain\Model\Model;

class TopicModel extends Model {

	public static function tableName() {
        return 'micro_topic';
    }

}