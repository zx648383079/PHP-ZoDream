<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Shop\Domain\Models\BrandModel;
use Module\Shop\Domain\Models\CategoryModel;
use Module\Shop\Domain\Models\GoodsModel;
use Zodream\Database\Contracts\SqlBuilder;

class SearchRepository {

    public static function hotKeywords(): array {
        return GoodsModel::query()->limit(5)->pluck('name');
    }

    public static function filterItems(string $keywords = '', int $category = 0, int $brand = 0) {
        $priceRange = GoodsModel::query()->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, 'name');
        })->when($category > 0, function ($query) use ($category) {
            $query->where('cat_id', $category);
        })->when($brand > 0, function ($query) use ($brand) {
            $query->where('brand_id', $brand);
        })->selectRaw('MAX(price) as max,MIN(price) as min')->first();
        $items = [
            static::renderCategory(CategoryRepository::getList($category)),
            static::renderBrand(BrandRepository::getList()),
        ];
        if ($priceRange) {
            $items[] = static::renderPrice(floatval($priceRange['min']), floatval($priceRange['max']));
        }
        return array_values(array_filter($items, function ($item) {
            return !empty($item) && !empty($item['items']);
        }));
    }

    public static function filterPrice(SqlBuilder $builder, string $price = '') {
        if (empty($price)) {
            return;
        }
        $args = explode('-', $price);
        $args[0] = trim($args[0]);
        if ($args[0] !== '') {
            $builder->where('price', '>=', floatval($args[0]));
        }
        if (!empty($args[1])) {
            $builder->where('price', '<=', floatval($args[1]));
        }
    }

    /**
     * 根据查询语句生成，数据多的化不建议使用，原理：通过遍历每一条数据进行统计
     * @param SqlBuilder $builder
     * @return array
     */
    public static function renderQueryFilter(SqlBuilder $builder) {
        $catItems = [];
        $brandItems = [];
        $priceItems = [];
        $maxPrice = 0;
        $minPrice = 0;
        $builder->each(function ($item) use (&$brandItems, &$catItems, &$maxPrice, &$minPrice, &$priceItems) {
            if (isset($catItems[$item['cat_id']])) {
                $catItems[$item['cat_id']] ++;
            } else {
                $catItems[$item['cat_id']] = 1;
            }
            if (isset($brandItems[$item['brand_id']])) {
                $brandItems[$item['brand_id']] ++;
            } else {
                $brandItems[$item['brand_id']] = 1;
            }
            if (isset($priceItems[$item['price']])) {
                $priceItems[$item['price']] ++;
            } else {
                $priceItems[$item['price']] = 1;
            }
            if ($minPrice <= 0 || $item['price'] < $minPrice) {
                $minPrice = $item['price'];
            }
            if ($maxPrice <= 0 || $item['price'] > $maxPrice) {
                $maxPrice = $item['price'];
            }
        }, 'cat_id', 'brand_id', 'price');
        $items = [
            static::renderCategory(CategoryModel::whereIn('id', array_keys($catItems))->get(), $catItems),
            static::renderBrand(BrandModel::whereIn('id', array_keys($brandItems))->get(), $brandItems),
            static::renderPrice($minPrice, $maxPrice, $priceItems)
        ];
        return array_values(array_filter($items, function ($item) {
            return !empty($item) && !empty($item['items']);
        }, ARRAY_FILTER_USE_BOTH));
    }


    protected static function renderPrice(float $min, float $max, array $countItems = []) {
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
                'label' => '不限',
                'count' => array_sum($countItems)
            ]
        ];
        while ($start < $max) {
            $next = $start + $step;
            $count = 0;
            foreach ($countItems as $i => $c) {
                if ($i >= $start && $i <= $next) {
                    $count += $c;
                }
            }
            $label = sprintf('%d-%d', $start, $next);
            $data[] = [
                'value' => $label,
                'label' => $label,
                'count' => $count
            ];
            $start = $next;
        }
        return [
            'name' => 'price',
            'label' => '价格',
            'items' => $data
        ];
    }

    protected static function renderCategory(array $items, array $countItems = []) {
        if (empty($items)) {
            return null;
        }
        $data = [];
        foreach ($items as $item) {
            $data[] = [
                'value' => $item['id'],
                'label' => $item['name'],
                'count' => $countItems[$item['id']] ?? 0
            ];
        }
        return [
            'name' => 'category',
            'label' => '分类',
            'items' => $data
        ];
    }

    private static function renderBrand(array $items, array $countItems = []) {
        if (empty($items)) {
            return null;
        }
        $data = [];
        foreach ($items as $item) {
            $data[] = [
                'value' => $item['id'],
                'label' => $item['name'],
                'count' => $countItems[$item['id']] ?? 0
            ];
        }
        return [
            'name' => 'brand',
            'label' => '品牌',
            'items' => $data
        ];
    }
}