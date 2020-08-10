<?php
namespace Module\Auth\Domain\Model;


use Domain\Model\Model;

class CreditLogModel extends Model {

    public static function tableName() {
        return 'user_credit_log';
    }

}