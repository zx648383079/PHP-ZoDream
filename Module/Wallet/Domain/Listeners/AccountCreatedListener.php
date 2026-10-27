<?php
declare(strict_types=1);
namespace Module\Wallet\Domain\Listeners;

use Module\Auth\Domain\Entities\UserEntity;
use Module\Auth\Domain\Events\AccountCreated;
use Module\Auth\Domain\Repositories\AuthRepository;
use Module\Wallet\Domain\Entities\WalletEntity;

final class AccountCreatedListener {

    public function __construct(AccountCreated $event) {
        WalletEntity::query()->insert([
            'id' => $event->getUserId(),
            'money' => 0,
            'credits' => 0,
            'password' => AuthRepository::UNSET_PASSWORD,
            'updated_at' => $event->getTimestamp(),
            'created_at' => $event->getTimestamp(),
        ]);
    }

    public static function syncUsers() {
        $time = time();
        $userIds = UserEntity::query()->pluck('id');
        if (empty($userIds)) {
            return;
        }
        $exist = WalletEntity::query()->pluck('id');
        $items = [];
        foreach ($userIds as $id) {
            if (in_array($id, $exist)) {
                continue;
            }
            $items[] = [
                'id' => $id,
                'money' => 0,
                'credits' => 0,
                'password' => AuthRepository::UNSET_PASSWORD,
                'updated_at' => $time,
                'created_at' => $time,
            ];
        }
        if (empty($items)) {
            return;
        }
        WalletEntity::query()->insert($items);
    }
}