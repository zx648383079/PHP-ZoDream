<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories;

use Module\Shop\Domain\Models\CollectModel;
use Module\Shop\Domain\Models\Scene\Collect;

class CollectRepository {

    public static function getList() {
        return Collect::with('goods')->page();
    }

    public static function add(int $goods) {
        if (!CollectModel::exist($goods)) {
            CollectModel::add($goods);
        }
    }

    public static function remove(int|array $goods) {
        CollectModel::when(is_array($goods), function ($query) use ($goods) {
                $query->whereIn('goods_id', $goods);
            }, function ($query) use ($goods) {
                $query->where('goods_id', $goods);
            })
            ->where('user_id', auth()->id())->delete();
    }

    public static function toggle(int $goods): bool {
        if (CollectModel::exist($goods)) {
            CollectModel::remove($goods);
            return false;
        }
        CollectModel::add($goods);
        return true;
    }
}