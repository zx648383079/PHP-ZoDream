<?php
declare(strict_types=1);
namespace Module\TradeTracker\Domain\Entities;

use Domain\Entities\Entity;
/**
 * 
 * @property integer $id
 * @property integer $product_id
 * @property integer $channel_id
 * @property string $platform_no
 * @property string $extra_meta
 * @property integer $updated_at
 * @property integer $created_at
 */
class ChannelProductEntity extends Entity {

    public static function tableName(): string {
        return 'tt_channel_product';
    }

    protected function rules(): array {
		return [
            'product_id' => 'required|int',
            'channel_id' => 'required|int',
            'platform_no' => 'string:0,40',
            'extra_meta' => 'string:0,255',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
	}

	protected function labels(): array {
		return [
            'id' => 'Id',
            'product_id' => 'Product Id',
            'channel_id' => 'Channel Id',
            'platform_no' => 'Platform No',
            'extra_meta' => 'Extra Meta',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
	}
}