<?php
namespace Module\Finance\Domain\Repositories;

use Module\Finance\Domain\Model\AccountSimpleModel;

class AccountRepository {

    /**
     * 获取简单的select
     * @return array|null
     */
    public static function getItems() {
        return AccountSimpleModel::auth()->orderBy('id', 'asc')->get();
    }
}