<?php
namespace Module\MicroBlog\Domain\Model;

use Domain\Model\Model;

/**
 * Class TopicModel
 * @package Module\MicroBlog\Domain\Model
 * @property integer $id
 * @property string $name
 * @property integer $user_id
 * @property integer $updated_at
 * @property integer $created_at
 */
class TopicModel extends Model {

	public static function tableName() {
        return 'micro_topic';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,200',
            'user_id' => 'required|int',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'user_id' => 'User Id',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

}