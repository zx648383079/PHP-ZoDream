<?php
namespace Module\Demo\Domain\Model;

use Domain\Model\Model;

/**
 * Class LogModel
 * @package Module\Demo\Domain\Model
 * @property integer $id
 * @property integer $item_type
 * @property integer $item_id
 * @property integer $user_id
 * @property integer $action
 * @property string $ip
 * @property integer $created_at
 */
class LogModel extends Model {

    const TYPE_POST = 0;
    const ACTION_DOWNLOAD = 0;

    public static function tableName() {
        return 'demo_log';
    }

    protected function rules() {
        return [
            'item_type' => 'int:0,127',
            'item_id' => 'required|int',
            'user_id' => 'required|int',
            'action' => 'required|int',
            'ip' => 'string',
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
            'ip' => 'Ip',
            'created_at' => 'Created At',
        ];
    }
}