<?php
namespace Module\MicroBlog\Domain\Model;

use Domain\Model\Model;

class BlogTopicModel extends Model {

	public static function tableName() {
        return 'micro_blog_topic';
    }

}