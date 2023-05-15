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
 * @property integer $total_money
 * @property integer $status
 * @property string $remark
 * @property integer $created_at
 * @property integer $updated_at
 */
class AccountLogModel extends Model {

    const STATUS_WAITING_PAY = 0;
    const STATUS_PAID = 1;
    const STATUS_REFUND = 9;

    protected array $append = ['type_label', 'status_label'];

    public static function tableName() {
        return 'user_account_log';
    }

    protected function rules() {
        return [
            'user_id' => 'int',
            'type' => 'int:0,99',
            'item_id' => 'int',
            'money' => 'required|int',
            'total_money' => 'required|int',
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
            'total_money' => 'Total Money',
            'status' => 'Status',
            'remark' => 'Remark',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

    public function getTypeLabelAttribute() {
        $type = $this->getAttributeSource('type');
        return trans('account_type.'.$type);
    }

    public function getStatusLabelAttribute() {
        $type = $this->getAttributeSource('status');
        return trans('account_status.'.$type);
    }

    /**
     * 退款，并更改用户金额
     * @return bool|mixed
     * @throws \Exception
     */
    public function refund() {
        UserModel::query()->where('id', $this->user_id)
            ->updateDecrement('money', $this->money);
        $this->status = self::STATUS_REFUND;
        return $this->save();
    }

    /**
     * 设置支付，不操作用户余额
     * @return bool|mixed
     */
    public function paid() {
        $this->status = self::STATUS_PAID;
        return $this->save();
    }


}