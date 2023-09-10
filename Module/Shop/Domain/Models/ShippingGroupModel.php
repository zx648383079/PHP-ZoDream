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
 * @property integer $id
 * @property integer $shipping_id
 * @property float $first_step
 * @property float $first_fee
 * @property float $additional
 * @property float $additional_fee
 * @property float $free_step
 * @property int $is_all
 */
class ShippingGroupModel extends Model {
    public static function tableName(): string {
        return 'shop_shipping_group';
    }

    public function rules(): array {
        return [
            'shipping_id' => 'required|int',
            'first_step' => '',
            'first_fee' => '',
            'additional' => '',
            'additional_fee' => '',
            'free_step' => '',
            'is_all' => '',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'shipping_id' => 'Shipping Id',
            'first_step' => '首件/首重',
            'first_fee' => '运费',
            'additional' => '续件/续重',
            'additional_fee' => '续费',
            'free_step' => '免费标准',
            'is_all' => '全地区',
        ];
    }

    public function regions() {
        return $this->belongsToMany(RegionModel::class,
            ShippingRegionModel::class, 'group_id', 'region_id');
    }

    public function getRegionIdsAttribute() {
        return ShippingRegionModel::where('shipping_id', $this->shipping_id)
            ->where('group_id', $this->id)->pluck('region_id');
    }

    public function getRegionLabelAttribute() {
        $regions = $this->region_ids;
        if (empty($regions)) {
            return '';
        }
        $name = RegionModel::whereIn('id', array_splice($regions, 0, 5))
            ->pluck('full_name');
        return implode('、', $name);
    }

    public static function batchSave($shipping_id, $data) {
        if (empty($data)) {
            return;
        }
        static::where('shipping_id', $shipping_id)
            ->whereNotIn('id', $data['id'])->delete();
        foreach ($data['region'] as $i => $region) {
            $id = isset($data['id'][$i]) ? intval($data['id'][$i]) : 0;
            $group = compact('shipping_id');
            foreach (['first_step', 'first_fee', 'additional', 'additional_fee', 'free_step'] as $key) {
                $group[$key] = isset($data[$key][$i]) ? floatval($data[$key][$i]) : 0;
            }
            if ($id > 0) {
                static::where('id', $id)->update($group);
            } else {
                $id = static::query()->insert($group);
            }
            ShippingRegionModel::batchSave($shipping_id, $id, explode(',', $region));
        }

    }
}