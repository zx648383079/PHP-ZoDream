<?php
declare(strict_types=1);
namespace Module\TradeTracker\Domain\Entities;

use Domain\Entities\Entity;
/**
 * 
 * @property integer $id
 * @property integer $user_id
 * @property integer $product_id
 * @property integer $channel_id
 * @property float $price
 * @property float $sell_price
 * @property integer $sell_channel_id
 * @property integer $status
 * @property integer $updated_at
 * @property integer $created_at
 */
class UserProductEntity extends Entity {

    public static function tableName(): string {
        return 'tt_user_product';
    }

    protected function rules(): array {
		return [
            'user_id' => 'required|int',
            'product_id' => 'required|int',
            'channel_id' => 'required|int',
            'price' => 'required|numeric',
            'sell_price' => 'numeric',
            'sell_channel_id' => 'int',
            'status' => 'int',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
	}

	protected function labels(): array {
		return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'product_id' => 'Product Id',
            'channel_id' => 'Channel Id',
            'price' => 'Price',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
	}
}