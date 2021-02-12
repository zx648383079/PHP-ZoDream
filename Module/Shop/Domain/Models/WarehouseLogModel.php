<?php
namespace Module\Shop\Domain\Models;


use Domain\Model\Model;

class WarehouseLogModel extends Model {

    public static function tableName() {
        return 'shop_warehouse_log';
    }
}