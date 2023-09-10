<?php
namespace Module\Finance\Domain\Model;


use Carbon\Carbon;
use Domain\Model\Model;

/**
 * 理财项目
 * @package Module\Finance\Domain\Model
 * @property integer $id
 * @property string $name
 * @property string $alias
 * @property float $money
 * @property float $account_id
 * @property float $earnings
 * @property string $start_at
 * @property string $end_at
 * @property float $earnings_number
 * @property integer $product_id
 * @property integer $status
 * @property integer $deleted_at
 * @property integer $color
 * @property string $remark
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class FinancialProjectModel extends Model {

    public static function tableName(): string {
        return 'finance_financial_project';
    }


    protected function rules(): array {
        return [
            'name' => 'required|string:0,35',
            'alias' => 'required|string:0,50',
            'money' => 'required',
            'account_id' => 'int',
            'earnings' => '',
            'start_at' => '',
            'end_at' => '',
            'earnings_number' => '',
            'product_id' => 'int',
            'status' => 'int:0,9',
            'deleted_at' => 'int',
            'color' => 'int:0,9',
            'remark' => '',
            'user_id' => 'required|int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'ID',
            'name' => '配置项目',
            'alias' => '别名',
            'money' => '资金',
            'account_id' => '账户',
            'earnings' => '(预估)收益率',
            'start_at' => '起息日期',
            'end_at' => '到期日期',
            'earnings_number' => '到期收益',
            'product_id' => '产品',
            'bank_id' => '形态',
            'status' => '状态',
            'deleted_at' => '删除时间',
            'color' => '盈亏',
            'remark' => '备注',
            'user_id' => 'User Id',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public function scopeAuth($query) {
        return $query->where('user_id', auth()->id());
    }


    public function product() {
        return $this->hasOne(FinancialProductModel::class, 'id', 'product_id');
    }

    public function getWeekIncome() {
        $data = [0, 0, 0, 0, 0, 0, 0];
        $log_list = LogModel::auth()->week(time())->where('project_id', $this->id)->all();
        foreach ($log_list as $item) {
            $day = date('w', strtotime($item->happened_at));
            if ($day < 1) {
                $day = 7;
            }
            $data[$day - 1] += $item->money;
        }
        return $data;
    }
}