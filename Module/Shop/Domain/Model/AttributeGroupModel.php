<?php
namespace Module\Shop\Domain\Model;


use Domain\Model\Model;

class AttributeGroupModel extends Model {

    public static function tableName() {
        return 'shop_attribute_group';
    }
}