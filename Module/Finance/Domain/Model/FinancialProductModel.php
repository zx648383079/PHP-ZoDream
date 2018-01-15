<?php
namespace Module\Finance\Domain\Model;


use Domain\Model\Model;

/**
 * 理财产品
 */
class FinancialProductModel extends Model {

    public static function tableName() {
        return 'financial_product';
    }
}