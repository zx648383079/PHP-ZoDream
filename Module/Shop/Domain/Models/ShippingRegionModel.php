<?php
namespace Module\Shop\Domain\Models;

/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/12/15
 * Time: 19:07
 */
use Domain\Model\Model;

/**
 * Class PaymentModel
 * @package Domain\Model\Shopping
 * @property integer $shipping_id
 * @property integer $group_id
 * @property integer $region_id
 */
class ShippingRegionModel extends Model {
    public static function tableName(): string {
        return 'shop_shipping_region';
    }

    protected string $primaryKey = '';

    public function rules(): array {
        return [
            'shipping_id' => 'required|int',
            'group_id' => 'required|int',
            'region_id' => 'required|int',
        ];
    }

    protected function labels(): array {
        return [
            'shipping_id' => 'Id',
            'group_id' => 'Group Id',
            'region_id' => 'Region Id',
        ];
    }

    public static function batchSave($shipping_id, $group_id, $data) {
//        $exist = static::where(compact('shipping_id', 'group_id'))
//            ->pluck('region_id');
        $items = [];
        foreach ($data as $region_id) {
            $items[] = compact('shipping_id', 'group_id', 'region_id');
        }
        static::query()->insert($items);
    }
}