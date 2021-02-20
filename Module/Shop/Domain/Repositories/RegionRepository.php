<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Shop\Domain\Models\RegionModel;

class RegionRepository {

    public static function getList(int $parent = 0, string $keywords = '') {
        return RegionModel::where('parent_id', $parent)
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'name');
            })->all();
    }

    public static function getPath(int $id) {
        $path = [];
        $exit = [];
        $child = $id;
        while ($child > 0) {
            if (in_array($child, $exit)) {
                logger()->error(sprintf('[%s] 地址有误，请及时修复', implode(',', $exit)));
                return [];
            }
            $model = RegionModel::find($child);
            if (empty($model)) {
                return [];
            }
            $path[] = $model;
            $exit[] = $child;
            $child = $model->parent_id;
        }
        return array_reverse($path);
    }

    public static function search(string $keywords = '', int|array $id = 0) {
        return SearchModel::searchOption(
            RegionModel::query(),
            ['name'],
            $keywords,
            $id === 0 ? [] : compact('id')
        );
    }
}