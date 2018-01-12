<?php
namespace Module\Finance\Domain\Model;

use Domain\Model\Model;

class BudgetModel extends Model {
    public static function tableName() {
        return 'budget';
    }


}