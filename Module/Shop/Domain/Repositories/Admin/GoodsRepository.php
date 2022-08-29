<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories\Admin;

use Domain\Model\SearchModel;
use Exception;
use Module\Shop\Domain\Entities\AttributeEntity;
use Module\Shop\Domain\Models\AttributeModel;
use Module\Shop\Domain\Models\AttributeUniqueModel;
use Module\Shop\Domain\Models\CartModel;
use Module\Shop\Domain\Models\CollectModel;
use Module\Shop\Domain\Models\CommentModel;
use Module\Shop\Domain\Models\GoodsAttributeModel;
use Module\Shop\Domain\Models\GoodsCardModel;
use Module\Shop\Domain\Models\GoodsGalleryModel;
use Module\Shop\Domain\Models\GoodsIssueModel;
use Module\Shop\Domain\Models\GoodsMetaModel;
use Module\Shop\Domain\Models\GoodsModel;
use Module\Shop\Domain\Models\GoodsPageModel;
use Module\Shop\Domain\Models\GoodsSimpleModel;
use Module\Shop\Domain\Models\OrderGoodsModel;
use Module\Shop\Domain\Models\ProductModel;
use Zodream\Database\DB;
use Zodream\Database\Model\Query;
use Zodream\Helpers\Json;
use Zodream\Helpers\Str;
use Zodream\Html\Page;
use Zodream\Validate\Validator;

class GoodsRepository {

    public static function getList(array $id = [],
                                   int $category = 0,
                                   int $brand = 0,
                                   string $keywords = '',
                                   int $perPage = 20, string $sort = '', string $order = '', bool $trash = false) {
        return GoodsPageModel::sortBy($sort, $order)->with('category', 'brand')
            ->when(!empty($id), function ($query) use ($id) {
                $query->whereIn('id', array_map('intval', $id));
            })
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'name');
            })->when($category > 0, function ($query) use ($category) {
                $query->where('cat_id', $category);
            })->when($brand > 0, function ($query) use ($brand) {
                $query->where('brand_id', $brand);
            })->when($trash, function ($query) {
                $query->where('deleted_at', '>', 0);
            })->page($perPage);
    }
    public static function get(int $id) {
        return GoodsModel::findOrThrow($id, '商品不存在');
    }

    public static function getFull(int $id) {
        $goods = GoodsModel::findOrThrow($id, '商品不存在');
        $data = $goods->toArray();
        $data['gallery'] = $goods->gallery;
        return array_merge($data, GoodsMetaModel::getOrDefault($id));
    }

    public static function save(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = GoodsModel::findOrNew($id);
        $model->load($data);
        if (!$model->save() && $id < 1) {
            throw new \Exception($model->getFirstError());
        }
        if ($id < 1) {
            GoodsAttributeModel::where('goods_id', '<', 1)->update([
                'goods_id' => $model->id
            ]);
        }
        GoodsMetaModel::saveBatch($model->id, $data);
        if (isset($data['attr'])) {
            static::batchSaveAttribute($data['attr'], $model->id, $model->attribute_group_id);
        }
        if (isset($data['products'])) {
            static::batchSaveProduct(is_array($data['products']) ? $data['products'] :
                Json::decode($data['products']), $model->id);
        }
        if (isset($data['gallery'])) {
            static::batchSaveGallery($data['gallery'], $model->id);
        }
        return $model;
    }

    public static function batchSaveProduct(array $data, int $goodsId) {
        $exist = [];
        foreach ($data as $item) {
            $item = Validator::filter($item, [
                'id' => 'int',
                'price' => '',
                'market_price' => '',
                'stock' => 'int',
                'series_number' => 'string:0,50',
                'attributes' => 'string:0,100',
                'weight' => 'numeric'
            ]);
            $attributes = [];
            foreach (explode(ProductModel::ATTRIBUTE_LINK, $item['attributes']) as $label) {
                $attrId = GoodsAttributeModel::where('value', $label)
                    ->where('goods_id', $goodsId)->value('id');
                if ($attrId < 1) {
                    continue;
                }
                $attributes[] = $attrId;
            }
            sort($attributes);
            $item['attributes'] = implode(ProductModel::ATTRIBUTE_LINK, $attributes);
            $item['goods_id'] = $goodsId;
            $model = static::productSave($item);
            $exist[] = $model->id;
        }
        ProductModel::where('goods_id', $goodsId)
            ->whereNotIn('id', $exist)->delete();
    }

    public static function productSave(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = ProductModel::findOrNew($id);
        $model->load($data);
        if (!$model->save() && $id < 1) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function batchSaveAttribute(array $data, int $goodsId, int $groupId) {
        $allow = AttributeEntity::where('group_id', $groupId)->pluck('id');
        if (empty($allow)) {
            return;
        }
        $attrId = [];
        foreach ($data as $item) {
            if (!in_array($item['attribute_id'], $allow)) {
                continue;
            }
            $item = Validator::filter($item, [
                'id' => 'int',
                'attribute_id' => 'required|int',
                'value' => 'required|string:0,255',
                'price' => '',
            ]);
            $item['goods_id'] = $goodsId;
            $model = static::attributeSave($item);
            $attrId[] = $model->id;
        }
        GoodsAttributeModel::where('goods_id', $goodsId)
            ->whereNotIn('id', $attrId)->delete();
    }

    public static function attributeSave(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = GoodsAttributeModel::findOrNew($id);
        $model->load($data);
        if (!$model->save() && $id < 1) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function batchSaveGallery(array $files, int $goodsId) {
        $fileId = [];
        foreach ($files as $item) {
            $item = Validator::filter($item, [
                'id' => 'int',
                'goods_id' => 'required|int',
                'type' => 'int:0,100',
                'thumb' => 'required|string:0,255',
                'file' => 'required|string:0,255',
            ]);
            $item['goods_id'] = $goodsId;
            $fileModel = static::gallerySave($item);
            $fileId[] = $fileModel->id;
        }
        GoodsGalleryModel::where('goods_id', $goodsId)
            ->whereNotIn('id', $fileId)->delete();
    }

    public static function gallerySave(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = GoodsGalleryModel::findOrNew($id);
        $model->load($data);
        if (!$model->save() && $id < 1) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function remove(int $id, bool $trash = false) {
        if (!$trash) {
            GoodsModel::where('id', $id)->update([
                'deleted_at' => time()
            ]);
            return;
        }
        DB::db()->transaction(function () use ($id) {
            GoodsModel::where('deleted_at', '>', 0)
                ->where('id', $id)->delete();
            GoodsGalleryModel::where('goods_id', $id)->delete();
            GoodsAttributeModel::where('goods_id', $id)->delete();
            GoodsIssueModel::where('goods_id', $id)->delete();
            CartModel::where('goods_id', $id)->delete();
            GoodsCardModel::where('goods_id', $id)->delete();
            GoodsMetaModel::deleteBatch($id);
        });
    }

    public static function clearTrash() {
        $goodsIds = GoodsModel::where('deleted_at', '>', 0)->pluck('id');
        if (empty($goodsIds)) {
            return;
        }
        DB::db()->transaction(function () use ($goodsIds) {
            GoodsModel::whereIn('id', $goodsIds)->delete();
            GoodsGalleryModel::whereIn('goods_id', $goodsIds)->delete();
            GoodsAttributeModel::whereIn('goods_id', $goodsIds)->delete();
            GoodsIssueModel::whereIn('goods_id', $goodsIds)->delete();
            CartModel::whereIn('goods_id', $goodsIds)->delete();
            GoodsCardModel::whereIn('goods_id', $goodsIds)->delete();
            GoodsMetaModel::whereIn('goods_id', $goodsIds)->delete();
        });
    }

    public static function restoreTrash(int $id) {
        GoodsModel::where('deleted_at', '>', 0)->when($id > 0, function ($query) use ($id) {
            $query->where('id', $id);
        })->update([
            'deleted_at' => 0
        ]);
    }

    public static function goodsAction(int $id, array $data) {
        $model = static::get($id);
        $maps =  ['is_best', 'is_hot', 'is_new'];
        foreach ($data as $action => $val) {
            if (is_int($action)) {
                if (empty($val)) {
                    continue;
                }
                list($action, $val) = [$val, $model->{$val} > 0 ? 0 : 1];
            }
            if (empty($action) || !in_array($action, $maps)) {
                continue;
            }
            if (!static::can($model, $action)) {
                throw new Exception('无权限');
            }
            $model->{$action} = intval($val);
        }
        $model->save();
        return $model;
    }

    public static function can(GoodsModel $model, string $action): bool {
        if (auth()->guest()) {
            return false;
        }
        return auth()->user()->hasRole('administrator');
    }

    public static function search(string $keywords = '', int $category = 0, int $brand = 0, int|array $id = 0) {
        if (!empty($keywords) && $category < 1 && $brand < 1) {
            $product = ProductModel::where('series_number', $keywords)
                ->first();
            if (!empty($product)) {
                $goods = GoodsSimpleModel::where('id', $product->goods_id)
                    ->first();
                $goods->products = [$product];
                return new Page([$goods]);
            }
            $goods = GoodsSimpleModel::where('series_number', $keywords)
                ->first();
            if (!empty($product)) {
                $goods->products;
                return new Page([$goods]);
            }
        }
        return GoodsSimpleModel::with('products')
            ->when(!empty($id), function ($query) use ($id) {
                if (is_array($id)) {
                    $query->whereIn('id', $id);
                    return;
                }
                $query->where('id', $id);
            })
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'name');
            })->when($category > 0, function ($query) use ($category) {
                $query->where('cat_id', $category);
            })->when($brand > 0, function ($query) use ($brand) {
                $query->where('brand_id', $brand);
            })->page();
    }

    public static function attributeList(int $group_id, int $goods_id = 0) {
        $attr_list = AttributeModel::where('group_id', $group_id)->orderBy('position asc')->orderBy('type asc')->asArray()->get();
        foreach ($attr_list as &$item) {
            $item['default_value'] = empty($item['default_value']) || $item['input_type'] < 1 ? [] : explode("\n", trim($item['default_value']));
            $item['attr_items'] = GoodsAttributeModel::where('goods_id', $goods_id)->where('attribute_id', $item['id'])->get();
        }
        unset($item);
        $product_list = ProductModel::where('goods_id', $goods_id)->orderBy('id asc')->get();
        return compact('attr_list', 'product_list');
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
            'series_number' => $data['sn'] ?? self::generateSn(),
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

    public static function cardList(int $goods, string $keywords = '') {
        return GoodsCardModel::where('goods_id', $goods)
            ->when(!empty($keywords), function ($query) use ($keywords) {
                $query->where('card_no', $keywords);
            })->orderBy('order_id', 'asc')->orderBy('id', 'desc')->page();
    }

    public static function cardGenerate(int $goods, int $amount = 1) {
        GoodsCardModel::generate($goods, $amount);
        GoodsCardModel::refreshStock($goods);
    }

    public static function cardRemove(int $id) {
        $model = GoodsCardModel::find($id);
        $model->delete();
        GoodsCardModel::refreshStock($model->goods_id);
    }

    public function cardImport(int $goods) {

    }

    public function cardExport(int $goods) {

    }

    /**
     * 整理商品id
     * @throws Exception
     */
    public static function sortOut() {
        set_time_limit(0);
        GoodsModel::refreshPk(function ($old_id, $new_id) {
            foreach ([
                         GoodsAttributeModel::class,
                         GoodsMetaModel::class,
                         GoodsGalleryModel::class,
                         GoodsCardModel::class,
                         GoodsIssueModel::class,
                         ProductModel::class,
                         CartModel::class,
                         OrderGoodsModel::class,
                         CollectModel::class,
                     ] as $class) {
                /** @var Query $query */
                $query = call_user_func($class.'::query');
                $query->where('goods_id', $old_id)->update([
                    'goods_id' => $new_id
                ]);
            }
            CommentModel::where('item_type', 0)->where('item_id', $old_id)->update([
                'item_id' => $new_id
            ]);
        });
    }
}