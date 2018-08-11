<?php
namespace Module\Shop\Domain\Model;


use Domain\Model\Model;

class AttributeModel extends Model {

    public static function tableName() {
        return 'shop_attribute';
    }
}