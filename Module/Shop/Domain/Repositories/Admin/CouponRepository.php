<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories\Admin;

use Domain\Model\SearchModel;
use Module\Shop\Domain\Models\CouponLogModel;
use Module\Shop\Domain\Models\CouponModel;

class CouponRepository {
    public static function getList(string $keywords = '') {
        return CouponModel::query()
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name']);
            })->orderBy('id', 'desc')->page();
    }

    public static function get(int $id) {
        return CouponModel::findOrThrow($id, '数据有误');
    }

    public static function save(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = CouponModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function remove(int $id) {
        CouponModel::where('id', $id)->delete();
    }

    public static function codeList(int $coupon, string $keywords = '') {
        return CouponLogModel::with('user')->where('coupon_id', $coupon)
            ->when(!empty($keywords), function ($query) use ($keywords) {
                $query->where('serial_number', $keywords);
            })->orderBy('id', 'desc')->page();
    }

    public static function codeGenerate(int $coupon, int $amount) {
        $data = [];
        $prefix = date('YmdHis');
        for (; $amount >= 0; $amount --) {
            $data[] = [
                'coupon_id' => $coupon,
                'serial_number' => $prefix.str_pad($amount.'', 6, '0', STR_PAD_LEFT),
            ];
        }
        if (empty($data)) {
            return;
        }
        CouponLogModel::query()->insert($data);
    }

    public static function codeAdd(int $coupon, array $items) {
        $data = [];
        foreach ($items as $item) {
            if (empty($item)) {
                continue;
            }
            $data[] = [
                'coupon_id' => $coupon,
                'serial_number' => $item
            ];
        }
        if (empty($data)) {
            return;
        }
        CouponLogModel::query()->insert($data);
    }
}