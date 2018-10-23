<?php
namespace Module\Finance\Domain\Model;


use Domain\Model\Model;

/**
 * 理财产品
 * @property string $name
 * @property integer $status
 * @property string $remark
 * @property integer $created_at
 * @property integer $updated_at
 */
class FinancialProductModel extends Model {

    public static function tableName() {
        return 'finance_financial_product';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,50',
            'status' => 'int:0,9',
            'remark' => '',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => '产品名',
            'status' => '状态',
            'remark' => '备注',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public function getMoneyAttribute() {
        return $this->money = floatval(FinancialProjectModel::where('product_id', $this->id)->sum('money'));
    }
}