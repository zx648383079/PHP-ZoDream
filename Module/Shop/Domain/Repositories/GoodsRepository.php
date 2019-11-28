<?php
namespace Module\Shop\Domain\Repositories;


use Module\Shop\Domain\Entities\GoodsEntity;
use Module\Shop\Domain\Models\GoodsModel;
use Module\Shop\Domain\Models\GoodsPageModel;
use Module\Shop\Domain\Models\GoodsSimpleModel;
use Zodream\Database\Model\Query;
use Zodream\Html\Page;

class GoodsRepository {

    public static function search(array $id = [],
                                  $category = 0,
                                  $brand = 0,
                                  $keywords = null,
                                  $per_page = 20, $sort = null, $order = null): Page {
        return GoodsSimpleModel::sortBy($sort, $order)
            ->when(!empty($id), function ($query) use ($id) {
                $query->whereIn('id', array_map('intval', $id));
            })
            ->when(!empty($keywords), function ($query) {
                GoodsEntity::search($query, 'name');
            })->when($category > 0, function ($query) use ($category) {
                $query->where('cat_id', intval($category));
            })->when($brand > 0, function ($query) use ($brand) {
                $query->where('brand_id', intval($brand));
            })->page($per_page);
    }

    public static function searchComplete(array $id = [],
                                  $category = 0,
                                  $brand = 0,
                                  $keywords = null,
                                  $per_page = 20, $sort = null, $order = null): Page {
        return GoodsPageModel::sortBy($sort, $order)->with('category', 'brand')
            ->when(!empty($id), function ($query) use ($id) {
                $query->whereIn('id', array_map('intval', $id));
            })
            ->when(!empty($keywords), function ($query) {
                GoodsEntity::search($query, 'name');
            })->when($category > 0, function ($query) use ($category) {
                $query->where('cat_id', intval($category));
            })->when($brand > 0, function ($query) use ($brand) {
                $query->where('brand_id', intval($brand));
            })->page($per_page);
    }

    public static function detail($id) {
        $goods = GoodsModel::find($id);
        if (empty($goods)) {
            return false;
        }
        $data = $goods->toArray();
        $data['properties'] = $goods->properties;
        $data['category'] = $goods->category;
        $data['brand'] = $goods->brand;
        $data['static_properties'] = $goods->static_properties;
        $data['is_collect'] = $goods->is_collect;
        $data['gallery'] = $goods->gallery;
        return $data;
    }

    public static function getRecommendQuery($tag): Query {
        return GoodsSimpleModel::where($tag, 1);
    }

    /**
     * @param Query $query
     * @param int $category
     * @param int $brand
     * @param null $keywords
     * @param int $per_page
     * @param $id
     * @return Page
     * @throws \Exception
     */
    public static function appendSearch(Query $query, $category = 0,
                                  $brand = 0,
                                  $keywords = null,
                                  $per_page = 20, array $id = []): Page {
        return $query->when(!empty($id), function (Query $query) use ($id) {
                $query->whereIn('id', array_map('intval', $id));
            })
            ->when(!empty($keywords), function (Query $query) {
                GoodsEntity::search($query, 'name');
            })->when($category > 0, function (Query $query) use ($category) {
                $query->where('cat_id', intval($category));
            })->when($brand > 0, function (Query $query) use ($brand) {
                $query->where('brand_id', intval($brand));
            })->page($per_page);
    }

    public static function importJson(array $data) {
        if (empty($data)) {
            return;
        }

    }
}