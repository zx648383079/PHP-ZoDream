<?php
declare(strict_types=1);
namespace Module\TradeTracker\Domain\Entities;

use Domain\Entities\Entity;
/**
 * 
 * @property integer $id
 * @property integer $user_id
 * @property integer $goods_id
 * @property integer $product_id
 * @property integer $channel_id
 * @property float $price
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
            'goods_id' => 'required|int',
            'product_id' => 'int',
            'channel_id' => 'required|int',
            'price' => 'required|string',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
	}

	protected function labels(): array {
		return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'goods_id' => 'Goods Id',
            'product_id' => 'Product Id',
            'channel_id' => 'Channel Id',
            'price' => 'Price',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
	}
}