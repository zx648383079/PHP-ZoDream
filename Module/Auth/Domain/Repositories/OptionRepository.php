<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Repositories;

use Module\Auth\Domain\Model\UserMetaModel;

class OptionRepository {

    const DEFAULT_ITEMS = [
        'accept_new_bulletin' => true,
        'open_not_disturb' => false,
        'post_expiration' => 0,
    ];

    public static function get(): array {
        return UserMetaModel::getMap(auth()->id(), static::DEFAULT_ITEMS);
    }

    public static function save(array $data) {
        UserMetaModel::saveBatch(auth()->id(), $data, static::DEFAULT_ITEMS);
        return static::get();
    }
}