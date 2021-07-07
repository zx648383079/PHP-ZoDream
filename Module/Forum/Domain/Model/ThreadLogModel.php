<?php
namespace Module\Forum\Domain\Model;

use Domain\Model\Model;
use Module\Auth\Domain\Model\UserSimpleModel;

/**
 * Class ThreadLogModel
 * @package Module\Forum\Domain\Model
 * @property integer $id
 * @property integer $item_type
 * @property integer $item_id
 * @property integer $user_id
 * @property integer $action
 * @property integer $node_index
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
    const ACTION_DOWNLOAD = 5;
    const ACTION_REWARD = 6; // 打赏


	public static function tableName() {
        return 'bbs_thread_log';
    }

    protected function rules() {
        return [
            'item_type' => 'int:0,127',
            'item_id' => 'required|int',
            'user_id' => 'required|int',
            'action' => 'required|int',
            'node_index' => 'int:0,127',
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
            'node_index' => 'Node Index',
            'data' => 'Data',
            'created_at' => 'Created At',
        ];
    }

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }
}