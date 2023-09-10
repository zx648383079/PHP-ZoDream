<?php
namespace Module\Shop\Domain\Models\Activity;

use Domain\Model\Model;
use Module\Auth\Domain\Model\UserSimpleModel;

/**
 * Class LotteryLogModel
 * @package Module\Shop\Domain\Models\Activity
 * @property integer $id
 * @property integer $act_id
 * @property integer $user_id
 * @property integer $item_type
 * @property integer $item_id
 * @property integer $amount
 * @property integer $status
 * @property integer $updated_at
 * @property integer $created_at
 */
class LotteryLogModel extends Model {

    public static function tableName(): string {
        return 'shop_lottery_log';
    }

    protected function rules(): array {
        return [
            'act_id' => 'required|int',
            'user_id' => 'required|int',
            'item_type' => 'int:0,127',
            'item_id' => 'int',
            'amount' => 'int',
            'status' => 'int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'act_id' => 'Act Id',
            'user_id' => 'User Id',
            'item_type' => 'Item Type',
            'item_id' => 'Item Id',
            'amount' => 'Amount',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    public function user()
    {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }
}