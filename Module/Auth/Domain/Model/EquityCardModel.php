<?php
namespace Module\Auth\Domain\Model;


use Domain\Model\Model;

class EquityCardModel extends Model {

    public static function tableName() {
        return 'user_equity_card';
    }

}