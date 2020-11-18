<?php
namespace Module\Book\Domain\Model;

use Domain\Model\Model;

/**
 * Class BookLogModel
 * @property integer $id
 * @property integer $item_type
 * @property integer $item_id
 * @property integer $user_id
 * @property integer $action
 * @property integer $created_at
 */
class BookLogModel extends Model {

    const TYPE_BOOK = 0;
    const TYPE_LIST = 1;

    const ACTION_CLICK = 0;
    const ACTION_COLLECT = 3;
    const ACTION_AGREE = 1;
    const ACTION_DISAGREE = 2;

    public static function tableName() {
        return 'book_log';
    }

    protected function rules() {
        return [
            'item_type' => 'int:0,127',
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