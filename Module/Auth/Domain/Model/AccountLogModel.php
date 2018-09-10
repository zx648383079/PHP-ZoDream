<?php
namespace Module\Auth\Domain\Model;


use Domain\Model\Model;

/**
 * Class AccountLogModel
 * @package Module\Auth\Domain\Model
 * @property integer $id
 * @property integer $user_id
 * @property integer $type
 * @property integer $item_id
 * @property integer $money
 * @property integer $status
 * @property string $remark
 * @property integer $created_at
 * @property integer $updated_at
 */
class AccountLogModel extends Model {

    const TYPE_DEFAULT = 99;

    public static function tableName() {
        return 'account_log';
    }

    protected function rules() {
        return [
            'user_id' => 'int',
            'type' => 'int:0,99',
            'item_id' => 'int',
            'money' => 'required|int',
            'status' => 'int:0,9',
            'remark' => 'required|string:0,255',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'type' => 'Type',
            'item_id' => 'Item Id',
            'money' => 'Money',
            'status' => 'Status',
            'remark' => 'Remark',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public static function log() {

    }

    public static function change() {

    }

    public static function isBought($id, $type = self::TYPE_DEFAULT) {
        return self::where('item_id', $id)
                ->where('type', $type)->count() > 0;
    }


}