<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories;

use Module\Shop\Domain\Models\GoodsModel;

class SearchRepository {

    public static function hotKeywords(): array {
        return GoodsModel::query()->limit(5)->pluck('name');
    }

    public static function filterItems(array $params) {
        $items = [
            static::renderCategory(CategoryRepository::getList()),
            static::renderBrand(BrandRepository::getList()),
            static::renderPrice(0, 2000)
        ];
        return array_filter($items, function ($item) {
            return !empty($item) && !empty($item['items']);
        });
    }


    protected static function renderPrice(float $min, float $max) {
        if ($min >= $max) {
            return null;
        }
        $start = $min = floor($min);
        $max = ceil($max);
        $maxCount = 10;
        $minStep = 30;
        $step = max(ceil(($max - $min) / $maxCount), $minStep);
        $data = [
            [
                'value' => '',
                'label' => '不限'
            ]
        ];
        while ($start < $max) {
            $next = $start + $step;
            $label = sprintf('%d-%d', $start, $next);
            $data[] = [
                'value' => $label,
                'label' => $label
            ];
            $start = $next;
        }
        return [
            'name' => 'price',
            'label' => '价格',
            'items' => $data
        ];
    }

    protected static function renderCategory(array $items) {
        if (empty($items)) {
            return null;
        }
        $data = [];
        foreach ($items as $item) {
            $data[] = [
                'value' => $item->id,
                'label' => $item->name
            ];
        }
        return [
            'name' => 'category',
            'label' => '分类',
            'items' => $data
        ];
    }

    private static function renderBrand(array $items) {
        if (empty($items)) {
            return null;
        }
        $data = [];
        foreach ($items as $item) {
            $data[] = [
                'value' => $item->id,
                'label' => $item->name
            ];
        }
        return [
            'name' => 'brand',
            'label' => '品牌',
            'items' => $data
        ];
    }
}