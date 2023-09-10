<?php
namespace Module\Finance\Domain\Model;


use Domain\Model\Model;

/**
 * 理财产品
 * @property string $name
 * @property integer $status
 * @property string $remark
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class FinancialProductModel extends Model {

    public static function tableName(): string {
        return 'finance_financial_product';
    }

    protected function rules(): array {
        return [
            'name' => 'required|string:0,50',
            'status' => 'int:0,9',
            'remark' => '',
            'user_id' => 'required|int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => '产品名',
            'status' => '状态',
            'remark' => '备注',
            'user_id' => 'User Id',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public function scopeAuth($query) {
        return $query->where('user_id', auth()->id());
    }

    public function getMoneyAttribute() {
        return $this->money = floatval(FinancialProjectModel::where('user_id', $this->user_id)->where('product_id', $this->id)->sum('money'));
    }
}