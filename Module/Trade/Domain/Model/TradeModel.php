<?php
namespace Module\Trade\Domain\Model;

use Domain\Model\Model;

class TradeModel extends Model {
    public static function tableName() {
        return 'trade';
    }
}