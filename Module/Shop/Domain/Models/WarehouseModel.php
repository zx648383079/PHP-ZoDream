<?php
namespace Module\Shop\Domain\Models;


use Domain\Model\Model;

class WarehouseModel extends Model {

    public static function tableName() {
        return 'shop_warehouse';
    }
}