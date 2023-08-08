<?php
declare(strict_types=1);
namespace Module\Catering\Domain\Repositories;

use Domain\Model\SearchModel;
use Exception;
use Module\Catering\Domain\Entities\RecipeEntity;

final class RecipeRepository {

    public static function merchantList(string $keywords = '') {
        return RecipeEntity::where('store_id', StoreRepository::own())
            ->when(!empty($keywords), function ($query) use ($keywords) {
                SearchModel::searchWhere($query, ['name'], true, '', $keywords);
            })->orderBy('id', 'desc')
            ->page();
    }
}