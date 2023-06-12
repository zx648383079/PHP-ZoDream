<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\SEO\Domain\Option;
use Module\Shop\Domain\Entities\GoodsEntity;
use Module\Shop\Domain\Models\GoodsDialogModel;
use Module\Shop\Domain\Models\GoodsMetaModel;
use Module\Shop\Domain\Models\GoodsModel;
use Module\Shop\Domain\Models\GoodsSimpleModel;
use Module\Shop\Domain\Models\ProductModel;
use Module\Shop\Domain\Repositories\Activity\ActivityRepository;
use Zodream\Database\Model\Query;
use Zodream\Html\Page;

class GoodsRepository {

    public static function search(array $id = [],
                                  int $category = 0,
                                  int $brand = 0,
                                  string $keywords = '',
                                  int $per_page = 20, $sort = null, $order = null, string $price = ''): Page {
        list($sort, $order) = SearchModel::checkSortOrder($sort, $order, ['id', 'name', 'price'], 'desc');
        return GoodsSimpleModel::when(!empty($id), function ($query) use ($id) {
                $query->whereIn('id', array_map('intval', $id));
            })
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'name');
            })->when($category > 0, function ($query) use ($category) {
                $query->where('cat_id', $category);
            })->when($brand > 0, function ($query) use ($brand) {
                $query->where('brand_id', $brand);
            })->when(!empty($price), function ($query) use ($price) {
                SearchRepository::filterPrice($query, $price);
            })->orderBy($sort, $order)->page($per_page);
    }

    /**
     * 商品详情
     * @param int $id
     * @return array|bool
     * @throws \Exception
     */
    public static function detail(int $id, bool $full = true, int $product = 0) {
        if ($product > 0 && $id < 1) {
            $id = intval(ProductModel::where('id', $product)->value('goods_id'));
        }
        $goods = GoodsModel::find($id);
        if (empty($goods)) {
            return false;
        }
        $data = $goods->toArray();
        $data = array_merge($data,
            GoodsMetaModel::getOrDefault($id),
            AttributeRepository::batchProperties($goods->attribute_group_id, $goods->id)
        );
        unset($data['cost_price']);
        $data['category'] = $goods->category;
        $data['brand'] = $goods->brand;
        $data['products'] = $goods->products;
        $data['is_collect'] = $goods->is_collect;
        $data['gallery'] = $goods->gallery;
        if ($full) {
            $data['countdown'] = self::getCountdown($goods);
            $data['promotes'] = self::getPromoteList($goods);
            $data['coupons'] = self::getCoupon($goods);
            $data['favorable_rate'] = CommentRepository::favorableRate($id);
            $data['services'] = [
                '48小时快速退款',
                '支持30天无忧退换货',
                '满88元免邮费',
                '自营品牌',
                '国内部分地区无法配送'
            ];
        }
        return $data;
    }

    public static function stock(int $goodsId, int $region = 0) {
        $goods = GoodsModel::findOrThrow($goodsId, '商品已下架');
        // 判断库存
        // 判断快递是否支持
        return $goods->toArray();
    }

    /**
     * 查询价格和库存
     * @param int $id
     * @param array $properties
     * @param int $amount
     * @param int $region
     * @return array
     */
    public static function price(int $id, array $properties = [], int $amount = 1, int $region = 0): array {
        $goods = GoodsModel::where('id', $id)->first('id', 'price', 'stock');
        $price = GoodsRepository::finalPrice($goods, $amount, $properties);
        $box = AttributeRepository::getProductAndPriceWithProperties($properties, $id);
        return [
            'price' => $price,
            'total' => $price * $amount,
            'stock' => !empty($box['product']) ? $box['product']->stock : $goods->stock
        ];
    }

    public static function formatProperties(GoodsModel $goods) {
        $data = $goods->toArray();
        unset($data['cost_price']);
        $data['properties'] = AttributeRepository::getProperties($goods->attribute_group_id, $goods->id);
        return $data;
    }

    public static function getDialog(int $goods) {
        return self::formatProperties(GoodsDialogModel::findOrThrow($goods));
    }

    public static function getCountdown(GoodsModel $goods) {
        return [
            'end_at' => time() + 3000,
            'name' => '秒杀',
            'tip' => '距秒杀结束还剩'
        ];
    }

    public static function getPromoteList(GoodsModel $goods) {
        return ActivityRepository::goodsJoin($goods);
    }

    public static function getCoupon(GoodsModel $goods) {
        return CouponRepository::goodsCanReceive($goods);
    }

    /**
     * 判断是否能购买指定数量
     * @param GoodsModel|int $goods
     * @param int $amount
     * @param int[] $properties
     * @return bool
     */
    public static function canBuy($goods, int $amount = 1, array $properties = []) {
        if (is_numeric($goods)) {
            $goods = GoodsModel::query()->where('id', $goods)
                ->first('id', 'price', 'stock');
        }
        if (empty($properties)) {
            return static::checkStock($goods, $amount);
        }
        $box = AttributeRepository::getProductAndPriceWithProperties($properties, $goods->id);
        if (empty($box['product'])) {
            return static::checkStock($goods, $amount);
        }
        return static::checkStock($box['product'], $amount);
    }

    public static function checkStock(ProductModel|GoodsEntity $model, int $amount = 0, int $regionId = 0): bool {
        if ($amount < 1) {
            return true;
        }
        if ($regionId < 1 || Option::value('shop_warehouse', 0) < 1) {
            return $model->stock >= $amount;
        }
        $goodsId = $model->id;
        $productId = 0;
        if ($model instanceof ProductModel) {
            $goodsId = $model->goods_id;
            $productId = $model->id;
        }
        return WarehouseRepository::getStock($regionId, $goodsId, $productId) >= $amount;
    }

    /**
     * 获取最终价格
     * @param GoodsModel|int $goods
     * @param int $amount
     * @param array $properties
     * @return float
     */
    public static function finalPrice(GoodsModel|int $goods, int $amount = 1, array $properties = []) {
        if (is_numeric($goods)) {
            $goods = CartRepository::getGoods(intval($goods));
        }
        if (empty($properties)) {
            return $goods->price;
        }
        $box = AttributeRepository::getProductAndPriceWithProperties($properties, $goods->id);
        if (empty($box['product'])) {
            return $goods->price + $box['properties_price'];
        }
        return $box['product']->price + $box['properties_price'];
    }

    public static function getRecommendQuery(string $tag): Query {
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
    public static function appendSearch(Query $query, int $category = 0,
                                  int $brand = 0,
                                  string $keywords = '',
                                  int $per_page = 20, array $id = []): Page {
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

    public static function homeRecommend(): array {
        $hot_products = GoodsRepository::getRecommendQuery('is_hot')->all();
        $new_products = GoodsRepository::getRecommendQuery('is_new')->all();
        $best_products = GoodsRepository::getRecommendQuery('is_best')->all();
        return compact('hot_products', 'new_products', 'best_products');
    }
}