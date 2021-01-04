<?php
namespace Module\Shop\Domain\Models;

/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/12/15
 * Time: 19:07
 */
use Domain\Model\Model;
use Module\Shop\Domain\Plugin\Manager;

/**
 * Class PaymentModel
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property integer $method
 * @property string $icon
 * @property string $description
 * @property integer $position
 * @property integer $created_at
 * @property integer $updated_at
 * @property array $settings
 */
class ShippingModel extends Model {
    public static function tableName() {
        return 'shop_shipping';
    }

    public function rules() {
        return [
            'name' => 'required|string:0,30',
            'code' => 'required|string:0,30',
            'method' => 'int:0,99',
            'icon' => 'string:0,100',
            'description' => 'string:0,255',
            'position' => 'int:0,99',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => '名称',
            'code' => 'Code',
            'method' => '计费方式',
            'icon' => '图标',
            'description' => '介绍',
            'position' => '排序',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function canUsePayment(PaymentModel $payment) {
        return true;
    }

    /**
     * @param AddressModel $address
     * @return static[]
     */
    public static function getByAddress(AddressModel $address) {
        $items = ShippingRegionModel::query()->where('region_id', $address->region_id)
            ->asArray()->get();
        if (empty($items)) {
            return [];
        }
        $groups = ShippingGroupModel::query()->whereIn('id', array_column($items, 'group_id'))
            ->asArray()->get();
        if (empty($groups)) {
            return [];
        }
        $groups = array_column($groups, null, 'shipping_id');
        $shipping_list = static::query()->whereIn('id', array_keys($groups))
            ->get();
        if (empty($shipping_list)) {
            return [];
        }
        foreach ($shipping_list as $item) {
            if (!isset($groups[$item->id])) {
                unset($item);
                continue;
            }
            $item->settings = $groups[$item->id];
        }
        return $shipping_list;
    }
}