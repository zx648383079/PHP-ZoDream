<?php
namespace Module\Shop\Domain\Model;


use Domain\Model\Model;

class WarehouseGoodsModel extends Model {

    public static function tableName() {
        return 'shop_warehouse_goods';
    }
}