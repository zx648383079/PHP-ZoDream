<?php
namespace Module\Trade\Domain\Model;

use Domain\Model\Model;

class RefundModel extends Model {
    public static function tableName(): string {
        return 'trade_refund';
    }
}