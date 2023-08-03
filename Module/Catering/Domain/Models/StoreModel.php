<?php
namespace Module\Catering\Domain\Models;

use Module\Auth\Domain\Model\UserSimpleModel;
use Module\Catering\Domain\Entities\StoreEntity;

class StoreModel extends StoreEntity {

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }
}