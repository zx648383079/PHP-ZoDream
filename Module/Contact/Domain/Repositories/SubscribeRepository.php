<?php
declare(strict_types=1);
namespace Module\Contact\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Contact\Domain\Model\SubscribeModel;

class SubscribeRepository {
    public static function getList(string $keywords = '') {
        return SubscribeModel::query()->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['email']);
        })->orderBy([
            'status' => 'asc',
            'id' => 'desc'
        ])->page();
    }

    public static function remove(int $id) {
        SubscribeModel::where('id', $id)->delete();
    }
}