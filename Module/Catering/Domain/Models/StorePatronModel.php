<?php
declare(strict_types=1);
namespace Module\Catering\Domain\Models;

use Module\Auth\Domain\Model\UserSimpleModel;
use Module\Catering\Domain\Entities\StorePatronEntity;

class StorePatronModel extends StorePatronEntity {

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }
}