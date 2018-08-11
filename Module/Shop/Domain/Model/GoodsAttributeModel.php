<?php
namespace Module\Shop\Domain\Model;


use Domain\Model\Model;

class GoodsAttributeModel extends Model {

    public static function tableName() {
        return 'shop_goods_attribute';
    }
}