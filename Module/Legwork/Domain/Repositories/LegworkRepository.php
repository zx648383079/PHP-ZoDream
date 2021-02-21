<?php
declare(strict_types=1);
namespace Module\Legwork\Domain\Repositories;

use Module\Legwork\Domain\Model\OrderModel;
use Module\Legwork\Domain\Model\ProviderModel;
use Module\Legwork\Domain\Model\WaiterModel;

class LegworkRepository {

    public static function role() {
        $user_id = auth()->id();
        return [
            'is_provider' => intval(ProviderModel::where('user_id', $user_id)->value('status')),
            'is_waiter' => intval(WaiterModel::where('user_id', $user_id)->value('status')),
        ];
    }

    public static function waitTaking(): int {
        return OrderModel::query()->where('waiter_id', 0)
            ->where('status', OrderModel::STATUS_PAID_UN_TAKING)->count();
    }
}