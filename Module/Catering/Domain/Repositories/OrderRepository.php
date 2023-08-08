<?php
declare(strict_types=1);
namespace Module\Catering\Domain\Repositories;

use Domain\Model\SearchModel;
use Exception;
use Module\Catering\Domain\Entities\OrderEntity;

final class OrderRepository {

    public static function getList(int $store = 0) {
        return OrderEntity::where('user_id', auth()->id())
            ->when($store > 0, function ($query) use ($store) {
                $query->where('store_id', $store);
            })
            ->orderBy('id', 'desc')
            ->page();
    }

    public static function merchantList(string $keywords = '', int $user = 0) {
        return OrderEntity::when(!empty($keywords), function ($query) use ($keywords) {
                SearchModel::searchWhere($query, ['name'], true, '', $keywords);
            })
            ->when($user > 0, function ($query) use ($user) {
                $query->where('user_id', $user);
            })
            ->where('store_id', StoreRepository::own())
            ->orderBy('id', 'desc')
            ->page();
    }
}