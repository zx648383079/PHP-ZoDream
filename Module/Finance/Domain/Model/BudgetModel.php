<?php
namespace Module\Finance\Domain\Model;

use Domain\Model\Model;

/**
 * 预算
 * @package Module\Finance\Domain\Model
 */
class BudgetModel extends Model {
    public static function tableName() {
        return 'budget';
    }


    public function getRemainAttribute() {
        return $this->budget - $this->spent;
    }
}