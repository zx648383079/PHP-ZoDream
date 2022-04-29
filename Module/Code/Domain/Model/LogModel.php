<?php
namespace Module\Code\Domain\Model;

use Domain\Model\Model;
/**
 * Class BlogLogModel
 * @property integer $id
 * @property integer $item_type
 * @property integer $item_id
 * @property integer $user_id
 * @property integer $action
 * @property integer $created_at
 */
class LogModel extends Model {

    const TYPE_CODE = 0;
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
            'item_type' => 'int:0,9',
            'item_id' => 'required|int',
            'user_id' => 'required|int',
            'action' => 'required|int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'item_type' => 'Type',
            'item_id' => 'Id Value',
            'user_id' => 'User Id',
            'action' => 'Action',
            'created_at' => 'Created At',
        ];
    }
}