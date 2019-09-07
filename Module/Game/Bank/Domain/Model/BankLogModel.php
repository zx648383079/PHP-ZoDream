<?php
namespace Module\Game\Bank\Domain\Model;


use Domain\Model\Model;
use Module\Auth\Domain\Model\AccountLogModel;
use Module\Auth\Domain\Model\UserSimpleModel;

/**
 * Class BankLogModel
 * @package Module\Game\Bank\Domain\Model
 * @property integer $id
 * @property integer $user_id
 * @property integer $product_id
 * @property integer $money
 * @property integer $real_money
 * @property integer $end_at
 * @property integer $earnings
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $income
 */
class BankLogModel extends Model {

    const STATUS_NONE = 0;
    const STATUS_FINISH = 1;

    public static function tableName() {
        return 'bank_log';
    }

    protected function rules() {
        return [
            'user_id' => 'required|int',
            'product_id' => 'required|int',
            'money' => 'required|int',
            'real_money' => 'required|int',
            'end_at' => 'int',
            'earnings' => 'int',
            'status' => 'int:0,9',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'product_id' => 'Product Id',
            'money' => '投资金额',
            'real_money' => '实际金额',
            'end_at' => '结束日期',
            'earnings' => '收益率',
            'status' => '状态',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function product() {
        return $this->hasOne(BankProductModel::class, 'id', 'product_id');
    }

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

    public function getIncomeAttribute() {
        return intval($this->earnings * $this->money / 10000);
    }

    /**
     * 投资
     * @param $user_id
     * @param $product_id
     * @param $money
     * @return bool
     * @throws \Exception
     */
    public static function invest($user_id, $product_id, $money) {
        $product = BankProductModel::find($product_id);
        if (empty($product) || $product->min_amount > $money) {
            return false;
        }

        $log = static::create([
            'user_id' => $user_id,
            'product_id' => $product->id,
            'money' => $money,
            'real_money' => $money,
            'end_at' => time() + $product->cycle * 86400,
            'earnings' => $product->earnings,
            'status' => 0,
        ]);
        if (empty($log)) {
            return false;
        }
        if (!AccountLogModel::change($user_id, AccountLogModel::TYPE_BANK, $log->id,
            -$money, sprintf('投资 %s 项目', $product->name), 1)) {
            $log->delete();
            return false;
        }
        return true;
    }

    public static function balance() {
        $log_list = static::with('product')->where('status', 0)
            ->where('end_at', time())->get();
        foreach ($log_list as $item) {
            /** @var $item static */
            $item->real_money = $item->money + $item->income;
            $item->status = static::STATUS_FINISH;
            $item->save();
            AccountLogModel::change($item->user_id,
                AccountLogModel::TYPE_BANK, $item->id,
                $item->real_money,
                sprintf('投资 %s 项目收益', $item->product->name), 1);
        }
    }
}