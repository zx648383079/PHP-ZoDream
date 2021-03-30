<?php
namespace Module\MicroBlog\Domain\Model;

use Domain\Model\Model;
/**
 * Class BlogLogModel
 * @property integer $id
 * @property integer $type
 * @property integer $id_value
 * @property integer $user_id
 * @property integer $action
 * @property integer $created_at
 */
class LogModel extends Model {

    const TYPE_MICRO_BLOG = 0;
    const TYPE_COMMENT = 1;

    const ACTION_RECOMMEND = 1;
    const ACTION_COLLECT = 2;
    const ACTION_AGREE = 3;
    const ACTION_DISAGREE = 4;


	public static function tableName() {
        return 'micro_log';
    }


    protected function rules() {
        return [
            'type' => 'int:0,9',
            'id_value' => 'required|int',
            'user_id' => 'required|int',
            'action' => 'required|int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'type' => 'Type',
            'id_value' => 'Id Value',
            'user_id' => 'User Id',
            'action' => 'Action',
            'created_at' => 'Created At',
        ];
    }

}