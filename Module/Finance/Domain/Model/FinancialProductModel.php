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
        return 'financial_product';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,50',
            'status' => 'int:0-1',
            'remark' => '',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'status' => 'Status',
            'remark' => 'Remark',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getMoneyAttribute() {
        return $this->money = floatval(FinancialProjectModel::where('product_id', $this->id)->sum('money'));
    }
}