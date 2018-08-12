<?php
namespace Module\Shop\Domain\Model;


use Domain\Model\Model;

class ProductAttributeModel extends Model {

    public static function tableName() {
        return 'shop_product_attribute';
    }
}