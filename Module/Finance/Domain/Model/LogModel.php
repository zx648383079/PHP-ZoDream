<?php
namespace Module\Finance\Domain\Model;

use Domain\Model\Model;

class LogModel extends Model {
    public static function tableName() {
        return 'money_log';
    }
}