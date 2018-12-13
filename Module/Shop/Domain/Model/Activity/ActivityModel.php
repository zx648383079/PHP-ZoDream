<?php
namespace Module\Shop\Domain\Model\Activity;


use Domain\Model\Model;

class ActivityModel extends Model {

    const TYPE_AUCTION = 1; // 拍卖
    const TYPE_SEC_KILL = 2; // 秒杀
    const TYPE_GROUP_BUY = 3; // 团购
    const TYPE_PACKAGE = 4; // 优惠

    const SCOPE_ALL = 0;
    const SCOPE_GOODS = 1;
    const SCOPE_BRAND = 2;
    const SCOPE_CATEGORY = 3;

    public static function tableName() {
        return 'shop_activity';
    }
}