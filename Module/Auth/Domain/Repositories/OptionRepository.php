<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Repositories;

use Module\Auth\Domain\Model\UserMetaModel;

class OptionRepository {

    public static function get(): array {
        return UserMetaModel::getOrDefault(auth()->id());
    }

    public static function save(array $data) {
        UserMetaModel::saveBatch(auth()->id(), $data);
        return static::get();
    }
}