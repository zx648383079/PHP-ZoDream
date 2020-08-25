<?php
namespace Module\Forum\Domain\Model;

use Domain\Model\Model;

/**
 * Class ThreadLogModel
 * @package Module\Forum\Domain\Model
 * @property integer $id
 * @property integer $item_type
 * @property integer $item_id
 * @property integer $user_id
 * @property integer $action
 * @property string $data
 * @property integer $created_at
 */
class ThreadLogModel extends Model {

    const TYPE_THREAD = 0;
    const TYPE_THREAD_POST = 1;
    const ACTION_COLLECT = 0;
    const ACTION_AGREE = 1;
    const ACTION_DISAGREE = 2;
    const ACTION_BUY = 3;
    const ACTION_VOTE = 4;


	public static function tableName() {
        return 'bbs_thread_log';
    }

    protected function rules() {
        return [
            'item_type' => 'int:0,127',
            'item_id' => 'required|int',
            'user_id' => 'required|int',
            'action' => 'required|int',
            'data' => 'string',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'item_type' => 'Item Type',
            'item_id' => 'Item Id',
            'user_id' => 'User Id',
            'action' => 'Action',
            'data' => 'Data',
            'created_at' => 'Created At',
        ];
    }


}