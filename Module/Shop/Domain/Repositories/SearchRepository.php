<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories;


use Module\Shop\Domain\Models\GoodsModel;

class SearchRepository {

    public static function hotKeywords(): array {
        return GoodsModel::query()->limit(5)->pluck('name');
    }
}