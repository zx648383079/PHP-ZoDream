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

    const TYPE_SYSTEM = 1; // 系统自动
    const TYPE_ADMIN = 9; // 管理员充值
    const TYPE_AFFILIATE = 15; // 分销
    const TYPE_BUY_BLOG = 21;
    const TYPE_DEFAULT = 99;
    const TYPE_CHECK_IN = 30;
    const TYPE_BANK = 31;
    const TYPE_GAME = 40;
    const TYPE_SHOPPING = 60;
    const STATUS_WAITING_PAY = 0;
    const STATUS_PAID = 1;
    const STATUS_REFUND = 9;

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

    /**
     * 退款，并更改用户金额
     * @return bool|mixed
     * @throws \Exception
     */
    public function refund() {
        UserModel::query()->where('id', $this->user_id)
            ->updateOne('money', - $this->money);
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

    /**
     * 创建一条记录
     * @param $user_id
     * @param $type
     * @param $item_id
     * @param $money
     * @param $total_money
     * @param $remark
     * @param int $status
     * @return AccountLogModel
     */
    public static function log(
        $user_id, $type, $item_id, $money, $total_money, $remark, $status = 0) {
        return static::create(
            compact('user_id', 'type', 'item_id', 'money', 'total_money', 'remark', 'status'));
    }

    /**
     * 更改用户金额，并记录
     * @param $user_id
     * @param $type
     * @param $item_id
     * @param $money
     * @param $remark
     * @param int $status
     * @return bool|int
     * @throws \Exception
     */
    public static function change(
        $user_id, $type, $item_id, $money, $remark, $status = 1) {
        if (empty($user_id)) {
            $user_id = auth()->id();
        }
        $old_money = UserModel::query()->where('id', $user_id)
            ->value('money');
        $new_money = floatval($old_money) + $money;
        if ($new_money < 0) {
            return false;
        }
        UserModel::query()->where('id', $user_id)->update([
            'money' => $new_money
        ]);
        $log = static::log($user_id, $type, $item_id, $money, $new_money, $remark, $status);
        if (auth()->id() === $user_id) {
            // 自动更新当前用户信息
            auth()->user()->moeny = $new_money;
        }
        return $log->id;
    }

    /**
     * 通过callback 同步创建记录修改余额
     * @param $user_id
     * @param $type
     * @param callable $cb 最好返回 model
     * @param $money
     * @param $total_money
     * @param $remark
     * @return bool|mixed
     * @throws \Exception
     */
    public static function changeAsync(
        $user_id, $type, callable $cb,
        $money, $total_money, $remark) {
        if (empty($user_id)) {
            $user_id = auth()->id();
        }
        $old_money = UserModel::query()->where('id', $user_id)
            ->value('money');
        $new_money = floatval($old_money) + $money;
        if ($new_money < 0) {
            return false;
        }
        $log = static::log($user_id, $type, 0, $money, $new_money, $remark, 0);
        if (empty($log)) {
            return false;
        }
        UserModel::query()->where('id', $user_id)->update([
            'money' => $new_money
        ]);
        $model = call_user_func($cb, $log);
        if ($model === false) {
            $log->refund();
            return false;
        }
        if (is_numeric($model)) {
            $log->item_id = $model;
        } elseif (isset($model['id'])) {
            $log->item_id = $model['id'];
        }
        $log->paid();
        return $model;
    }

    public static function isBought($id, $type = self::TYPE_DEFAULT) {
        return self::where('item_id', $id)
                ->where('type', $type)->count() > 0;
    }


}