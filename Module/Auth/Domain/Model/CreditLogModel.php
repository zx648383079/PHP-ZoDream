<?php
namespace Module\Auth\Domain\Model;


use Domain\Model\Model;

/**
 * 账户积分变动表
 * @property integer $id
 * @property integer $user_id
 * @property integer $type
 * @property integer $item_id
 * @property integer $credits
 * @property integer $total_credits
 * @property integer $status
 * @property string $remark
 * @property integer $created_at
 * @property integer $updated_at
 */
class CreditLogModel extends Model {

    public static function tableName() {
        return 'user_credit_log';
    }

    protected function rules() {
        return [
            'user_id' => 'int',
            'type' => 'int:0,127',
            'item_id' => 'int',
            'credits' => 'required|int',
            'total_credits' => 'required|int',
            'status' => 'int:0,127',
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
            'credits' => 'Credits',
            'total_credits' => 'Total Credits',
            'status' => 'Status',
            'remark' => 'Remark',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

}