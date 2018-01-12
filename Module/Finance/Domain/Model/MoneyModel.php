<?php
namespace Module\Finance\Domain\Model;

use Domain\Model\Model;

class MoneyModel extends Model {
    public static function tableName() {
        return 'money';
    }


}