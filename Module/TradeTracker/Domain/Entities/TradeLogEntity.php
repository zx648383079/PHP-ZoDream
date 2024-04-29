<?php
declare(strict_types=1);
namespace Module\TradeTracker\Domain\Entities;

use Domain\Entities\Entity;
/**
 * 
 * @property integer $id
 * @property integer $product_id
 * @property integer $channel_id
 * @property integer $type
 * @property float $price
 * @property integer $created_at
 */
class TradeLogEntity extends Entity {

    public static function tableName(): string {
        return 'tt_trade_log';
    }

    protected function rules(): array {
		return [
            'product_id' => 'required|int',
            'channel_id' => 'required|int',
            'type' => 'int:0,127',
            'price' => 'required|string',
            'created_at' => 'int',
        ];
	}

	protected function labels(): array {
		return [
            'id' => 'Id',
            'product_id' => 'Product Id',
            'channel_id' => 'Channel Id',
            'type' => 'Type',
            'price' => 'Price',
            'created_at' => 'Created At',
        ];
	}
}