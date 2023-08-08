<?php
declare(strict_types=1);
namespace Module\Catering\Domain\Repositories;

use Domain\Model\SearchModel;
use Exception;
use Module\Catering\Domain\Entities\AddressEntity;

final class AddressRepository {

    public static function getList() {
        return AddressEntity::where('user_id', auth()->id())
            ->orderBy('id', 'desc')
            ->page();
    }
}