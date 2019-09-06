<?php
namespace Module\Game\Bank\Domain\Model;


use Domain\Model\Model;

/**
 * Class BankProductModel
 * @package Module\Game\Bank\Domain\Model
 * @property integer $id
 * @property string $name
 * @property integer $min_amount
 * @property integer $cycle
 * @property integer $earnings
 * @property integer $risk
 */
class BankProductModel extends Model {
    public static function tableName() {
        return 'bank_product';
    }

    protected function rules() {
        return [
            'id' => 'required|int',
            'name' => 'string:0,255',
            'min_amount' => 'int:0,9999',
            'cycle' => 'int:0,9999',
            'earnings' => 'int',
            'risk' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => '产品名',
            'min_amount' => '最小投资额',
            'cycle' => '周期(天)',
            'earnings' => '收益率/10000',
            'risk' => '风险/10000',
        ];
    }

}