<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Repositories;

use Module\Auth\Domain\Helpers;
use Module\Auth\Domain\Model\UserMetaModel;

class OptionRepository {

    const DEFAULT_ITEMS = [
        'upload_add_water' => false,
        'accept_new_bulletin' => true,
        'open_not_disturb' => false,
        'post_expiration' => 0,
    ];

    public static function get(): array {
        $data = UserMetaModel::getMap(auth()->id(), static::DEFAULT_ITEMS);
        if (isset($data['id_card'])) {
            $data['id_card'] = Helpers::hideCard($data['id_card']);
        }
        return $data;
    }

    public static function save(array $data) {
        UserMetaModel::saveBatch(auth()->id(), $data, static::DEFAULT_ITEMS);
        return static::get();
    }
}