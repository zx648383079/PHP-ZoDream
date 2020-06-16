<?php
namespace Module\Shop\Domain\Repositories;


use Module\Shop\Domain\Entities\GoodsEntity;
use Module\Shop\Domain\Models\AttributeModel;
use Module\Shop\Domain\Models\GoodsGalleryModel;
use Module\Shop\Domain\Models\GoodsModel;
use Module\Shop\Domain\Models\GoodsPageModel;
use Module\Shop\Domain\Models\GoodsSimpleModel;
use Zodream\Database\Model\Query;
use Zodream\Helpers\Str;
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
                GoodsEntity::searchWhere($query, 'name');
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
                GoodsEntity::searchWhere($query, 'name');
            })->when($category > 0, function ($query) use ($category) {
                $query->where('cat_id', intval($category));
            })->when($brand > 0, function ($query) use ($brand) {
                $query->where('brand_id', intval($brand));
            })->page($per_page);
    }

    /**
     * 商品详情
     * @param $id
     * @return array|bool
     * @throws \Exception
     */
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
        // $data['countdown'] = self::getCountdown($id);
        // $data['promotes'] = self::getPromoteList($id);
        return $data;
    }

    public static function formatProperties(GoodsModel $goods) {
        if (empty($goods)) {
            return false;
        }
        $data = $goods->toArray();
        $data['properties'] = $goods->properties;
        return $data;
    }

    public static function getCountdown($id) {
        return [
            'end_at' => time() + 3000,
            'name' => '秒杀',
            'tip' => '距秒杀结束还剩'
        ];
    }

    public static function getPromoteList($id) {
        return [
            [
                'name' => '支付',
                'items' => [
                    [
                        'name' => '优惠',
                        'icon' => '领券',
                    ]
                ]
            ]
        ];
    }

    /**
     * 判断是否能购买指定数量
     * @param GoodsModel|int $goods
     * @param int $amount
     * @param null $properties
     * @return bool
     */
    public static function canBuy($goods, $amount = 1, $properties = null) {
        if (is_numeric($goods)) {
            $goods = GoodsModel::query()->where('id', $goods)
                ->first('id', 'price', 'stock');
        }
        if (empty($properties)) {
            return $goods->stock >= $amount;
        }
        $box = AttributeModel::getProductAndPriceWithProperties($properties, $goods->id);
        if (empty($box['product'])) {
            return $goods->stock >= $amount;
        }
        return $box['product']->stock >= $amount;
    }

    /**
     * 获取最终价格
     * @param GoodsModel|int $goods
     * @param int $amount
     * @param null $properties
     * @return float
     */
    public static function finalPrice($goods, $amount = 1, $properties = null) {
        if (is_numeric($goods)) {
            $goods = GoodsModel::query()->where('id', $goods)
                ->first('id', 'price', 'stock');
        }
        if (empty($properties)) {
            return $goods->price;
        }
        $box = AttributeModel::getProductAndPriceWithProperties($properties, $goods->id);
        if (empty($box['product'])) {
            return $goods->price + $box['properties_price'];
        }
        return $box['product']->price + $box['properties_price'];
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
                GoodsEntity::searchWhere($query, 'name');
            })->when($category > 0, function (Query $query) use ($category) {
                $query->where('cat_id', intval($category));
            })->when($brand > 0, function (Query $query) use ($brand) {
                $query->where('brand_id', intval($brand));
            })->page($per_page);
    }

    public static function importJson(array $data) {
        if (empty($data)) {
            throw new \Exception('数据错误');
        }
        if (isset($data['sn']) && self::hasSeriesNumber($data['sn'])) {
            throw new \Exception('商品已存在');
        }
        $goods = GoodsModel::create([
            'cat_id' => CategoryRepository::findOrNew($data['category']),
            'brand_id' => BrandRepository::findOrNew($data['brand']),
            'name' => $data['title'],
            'series_number' => isset($data['sn']) ? $data['sn'] : self::generateSn(),
            'keywords' => 'string:0,200',
            'thumb' => $data['thumb'],
            'picture' => $data['thumb'],
            'description' => $data['description'],
            'brief' => $data['description'],
            'content' => $data['content'],
            'price' => $data['price'],
            'market_price' => $data['price'],
            'stock' => 1,
            'status' => GoodsModel::STATUS_SALE,
        ]);
        if (!$goods) {
            throw new \Exception('创建失败');
        }
        if (empty($data['images'])) {
            return $goods;
        }
        $items = [];
        foreach ($data['images'] as $img) {
            $items[] = [
                'goods_id' => $goods->id,
                'image' => $img,
            ];
        }
        GoodsGalleryModel::query()->insert($items);
        return $goods;
    }

    public static function hasSeriesNumber($sn) {
        return GoodsModel::where('series_number', $sn)->count() > 0;
    }

    public static function generateSn() {
        $sn = time();
        $i = 0;
        while ($i < 10) {
            $i ++;
            $sn = 'SN'.str_pad(Str::randomNumber(8), 8, '0', STR_PAD_LEFT );
            if (!self::hasSeriesNumber($sn)) {
                break;
            }
        }
        return $sn;
    }
}