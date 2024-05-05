<?php
declare(strict_types=1);
namespace Module\TradeTracker\Domain\Models;

use Module\TradeTracker\Domain\Entities\ChannelEntity;
use Module\TradeTracker\Domain\Entities\ProductEntity;
use Module\TradeTracker\Domain\Entities\TradeEntity;

/**
 * 
 * @property integer $id
 * @property integer $product_id
 * @property integer $channel_id
 * @property integer $type
 * @property float $price
 * @property integer $order_count
 * @property integer $created_at
 */
class TradeModel extends TradeEntity {

    public function product() {
        return $this->hasOne(ProductEntity::class, 'id', 'product_id');
    }

    public function channel() {
        return $this->hasOne(ChannelEntity::class, 'id', 'channel_id');
    }
}