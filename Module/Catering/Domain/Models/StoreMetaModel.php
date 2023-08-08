<?php
namespace Module\Catering\Domain\Models;

use Domain\Concerns\TableMeta;
use Module\Catering\Domain\Entities\StoreMetaEntity;

class StoreMetaModel extends StoreMetaEntity {

    use TableMeta;

    protected static string $idKey = 'store_id';
    protected static array $defaultItems = [
        'is_open_live' => 1, // 是否支持到店点餐
        'is_open_ship' => 0, // 是否支持外送
        'is_ship_self' => 0, // 是否支持外卖订单上门自取
        'is_open_reserve' => 0, // 是否支持提前预定
        'reserve_time' => 0, // 支持提前多久预定
    ];
}