<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories\Admin;

use Domain\Model\SearchModel;
use Module\Shop\Domain\Models\Logistics\DeliveryGoodsModel;
use Module\Shop\Domain\Models\Logistics\DeliveryModel;
use Zodream\Helpers\Json;

class DeliveryRepository {
    public static function getList(string $keywords = '') {
        return DeliveryModel::query()
            ->with('goods', 'user', 'order')
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name', 'shipping_name', 'logistics_number', 'tel']);
            })->orderBy('status', 'asc')
            ->orderBy('id', 'desc')->page();
    }

    public static function remove(int $id) {
        DeliveryModel::where('id', $id)->delete();
        DeliveryGoodsModel::where('delivery_id', $id)->delete();
    }

    public static function save(int $id, array $data) {
        $model = DeliveryModel::findOrThrow($id, '数据错误');
        if (isset($data['logistics_content'])) {
            $model->logistics_content = Json::encode($data['logistics_content']);
            if (!isset($data['status'])) {
                $data['status'] = end($data['logistics_content'])['status'];
            }
        }
        if (isset($data['status'])) {
            $model->status = $data['status'];
        }
        $model->save();
        return $model;
    }

}