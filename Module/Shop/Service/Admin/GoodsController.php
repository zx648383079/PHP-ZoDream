<?php
namespace Module\Shop\Service\Admin;


use Module\Shop\Domain\Models\AttributeGroupModel;
use Module\Shop\Domain\Models\AttributeModel;
use Module\Shop\Domain\Models\AttributeUniqueModel;
use Module\Shop\Domain\Models\BrandModel;
use Module\Shop\Domain\Models\CartModel;
use Module\Shop\Domain\Models\CategoryModel;
use Module\Shop\Domain\Models\CollectModel;
use Module\Shop\Domain\Models\CommentModel;
use Module\Shop\Domain\Models\GoodsAttributeModel;
use Module\Shop\Domain\Models\GoodsCardModel;
use Module\Shop\Domain\Models\GoodsGalleryModel;
use Module\Shop\Domain\Models\GoodsIssueModel;
use Module\Shop\Domain\Models\GoodsModel;
use Module\Shop\Domain\Models\GoodsSimpleModel;
use Module\Shop\Domain\Models\OrderGoodsModel;
use Module\Shop\Domain\Models\ProductModel;
use Module\Shop\Domain\Repositories\GoodsRepository;
use Zodream\Helpers\Json;
use Zodream\Infrastructure\Http\Request;

class GoodsController extends Controller {

    public function indexAction($keywords = null, $sort = null, $cat_id = 0, $brand_id = 0, $trash = false) {
        $model_list = GoodsModel::with('category', 'brand')
            ->when(!empty($keywords), function ($query) {
                $query->where(function ($query) {
                    GoodsModel::search($query, 'name');
                });
            })->when(!empty($cat_id), function ($query) use ($cat_id) {
                $query->where('cat_id', intval($cat_id));
            })->when(!empty($brand_id), function ($query) use ($brand_id) {
                $query->where('brand_id', intval($brand_id));
            })->when(!empty($sort), function ($query) use ($sort) {
                if (in_array($sort, ['is_best', 'is_hot', 'is_new'])) {
                    $query->where($sort, 1);
                }
            })->when($trash, function ($query) {
                $query->where('deleted_at', '>', 0);
            }, function ($query) {
                $query->where('deleted_at', 0);
            })->orderBy('id', 'desc')->page();
        $cat_list = CategoryModel::tree()->makeTreeForHtml();
        $brand_list = BrandModel::select('id', 'name')->all();
        return $this->show(compact('model_list', 'cat_list', 'brand_list', 'keywords', 'cat_id', 'brand_id', 'sort'));
    }

    public function trashAction($keywords = null, $cat_id = 0, $brand_id = 0) {
        return $this->indexAction($keywords, $cat_id, $brand_id, true);
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = GoodsModel::findOrDefault($id, [
            'stock' => 1, 'weight' => 0, 'status' => GoodsModel::STATUS_SALE]);
        $cat_list = CategoryModel::tree()->makeTreeForHtml();
        $brand_list = BrandModel::select('id', 'name')->all();
        $group_list = AttributeGroupModel::all();
        $gallery_list = GoodsGalleryModel::where('goods_id', $id)->all();
        return $this->show(compact('model', 'cat_list', 'brand_list', 'group_list', 'gallery_list'));
    }

    public function saveAction($id, $product = null, $gallery = null, $attr = null) {
        $model = new GoodsModel();
        if (!$model->load() || !$model->autoIsNew()->save()) {
            return $this->renderFailure($model->getFirstError());
        }
        if ($id < 1) {
            GoodsAttributeModel::where('goods_id', '<', 1)->update([
                'goods_id' => $model->id
            ]);
        }
        if (!empty($product)) {
            ProductModel::batchSave(is_array($product) ? $product : Json::decode($product), $model->id);
        }
        if (!empty($gallery)) {
            GoodsGalleryModel::batchSave($gallery, $model->id);
        }
        if (!empty($attr)) {
            AttributeUniqueModel::batchSave($model, $attr);
        }
        return $this->renderData([
            'url' => $this->getUrl('goods')
        ]);
    }

    public function deleteAction($id, $trash = false) {
        if ($trash) {
            GoodsModel::where('deleted_at', '>', 0)
                ->where('id', $id)->delete();
        } else {
            GoodsModel::where('id', $id)->update([
                'deleted_at' => time()
            ]);
        }
        return $this->renderData([
            'url' => $this->getUrl('goods')
        ]);
    }

    public function clearAction() {
        GoodsModel::where('deleted_at', '>', 0)->delete();
        return $this->renderData([
            'url' => $this->getUrl('goods')
        ]);
    }

    public function restoreAction($id = 0) {
        GoodsModel::where('deleted_at', '>', 0)->when($id > 0, function ($query) use ($id) {
            $query->where('id', intval($id));
        })->update([
            'deleted_at' => 0
        ]);
        return $this->renderData([
            'url' => $this->getUrl('goods')
        ]);
    }

    public function toggleAction($id, $name) {
        if ($id < 1 || !in_array($name, ['is_best', 'is_hot', 'is_new'])) {
            return $this->renderFailure('信息错误！');
        }
        GoodsModel::where('id', $id)->updateBool($name);
        return $this->renderData();
    }

    public function generateSnAction() {
        $sn = GoodsRepository::generateSn();
        return $this->renderData($sn);
    }

    public function attributeAction($group_id, $goods_id = 0) {
        $attr_list = AttributeModel::where('group_id', $group_id)->orderBy('position asc')->orderBy('type asc')->asArray()->all();
        foreach ($attr_list as &$item) {
            $item['default_value'] = empty($item['default_value']) || $item['input_type'] < 1 ? [] : explode(PHP_EOL, $item['default_value']);
            $item['attr_items'] = GoodsAttributeModel::where('goods_id', $goods_id)->where('attribute_id', $item['id'])->all();
        }
        unset($item);
        $product_list = ProductModel::where('goods_id', $goods_id)->orderBy('id asc')->all();
        return $this->renderData(compact('attr_list', 'product_list'));
    }

    public function saveAttributeAction() {
        $model = new GoodsAttributeModel();
        if (!$model->load()) {
            return $this->renderFailure($model->getFirstError());
        }
        $model->autoIsNew();
        if (!$model->checkValue()) {
            return $this->renderFailure('属性值已存在！');
        }
        if (!$model->save()) {
            return $this->renderFailure($model->getFirstError());
        }
        return $this->renderData($model->toArray());
    }

    public function editAttributeAction($attr, $goods_id = 0) {
        $models = GoodsAttributeModel::batchSave($attr, $goods_id);
        if (empty($models)) {
            return $this->renderFailure('更新失败！');
        }
        return $this->renderData(count($models) == 1 ? reset($models) : $models);
    }

    public function deleteAttributeAction($attribute_id = 0, $value = 0, $goods_id = 0, $id = 0) {
        if ($id > 0) {
            GoodsAttributeModel::where('id', $id)->delete();
        } else {
            GoodsAttributeModel::where('goods_id', $goods_id)->where('attribute_id', $attribute_id)->where('value', $value)->delete();
        }
        return $this->renderData();
    }

    public function cardAction($id, $keywords = null) {
        $model = GoodsModel::find($id);
        if (empty($model)) {
            return $this->redirect($this->getUrl('goods'));
        }
        $card_list = GoodsCardModel::where('goods_id', $id)
            ->when(!empty($keywords), function ($query) use ($keywords) {
                $query->where('card_no', $keywords);
            })->orderBy('order_id', 'asc')->orderBy('id', 'desc')->page();
        return $this->show(compact('card_list', 'model', 'keywords'));
    }

    public function importCardAction($id) {

    }

    public function exportCardAction($id) {

    }

    public function createCardAction($id, $amount = 1) {
        GoodsCardModel::generate($id, $amount);
        GoodsCardModel::refreshStock($id);
        return $this->renderData([
            'refresh' => true
        ]);
    }

    public function deleteCardAction($id) {
        $model = GoodsCardModel::find($id);
        $model->delete();
        GoodsCardModel::refreshStock($model->goods_id);
        return $this->renderData([
            'refresh' => true
        ]);
    }

    public function refreshAction() {
        set_time_limit(0);
        GoodsModel::refreshPk(function ($old_id, $new_id) {
            GoodsAttributeModel::where('goods_id', $old_id)->update([
                'goods_id' => $new_id
            ]);
            GoodsGalleryModel::where('goods_id', $old_id)->update([
                'goods_id' => $new_id
            ]);
            GoodsIssueModel::where('goods_id', $old_id)->update([
                'goods_id' => $new_id
            ]);
            ProductModel::where('goods_id', $old_id)->update([
                'goods_id' => $new_id
            ]);
            CartModel::where('goods_id', $old_id)->update([
                'goods_id' => $new_id
            ]);
            OrderGoodsModel::where('goods_id', $old_id)->update([
                'goods_id' => $new_id
            ]);
            CollectModel::where('goods_id', $old_id)->update([
                'goods_id' => $new_id
            ]);
            CommentModel::where('item_type', 0)->where('item_id', $old_id)->update([
                'item_id' => $new_id
            ]);
        });
        return $this->renderData([
            'refresh' => true
        ]);
    }

    public function dialogAction(
        $keywords = null,
        $cat_id = 0,
        $brand_id = 0, $selected = [],
        $just_selected = false,
        $simple = false) {
        $selected = static::parseArrInt($selected);
        $simple = !!$simple;
        $model_list = GoodsSimpleModel::when(!empty($keywords), function ($query) {
                $query->where(function ($query) {
                    GoodsModel::search($query, 'name');
                });
            })->when(!empty($cat_id), function ($query) use ($cat_id) {
                $query->where('cat_id', intval($cat_id));
            })->when(!empty($brand_id), function ($query) use ($brand_id) {
                $query->where('brand_id', intval($brand_id));
            })->when($just_selected, function ($query) use ($selected) {
            $query->whereIn('id', $selected);
            })->orderBy('id', 'desc')->page();
        if (app('request')->wantsJson()) {
            return $this->renderData($model_list);
        }
        if (!$simple) {
            $cat_list = CategoryModel::tree()->makeTreeForHtml();
            $brand_list = BrandModel::select('id', 'name')->all();
            $this->send(compact('cat_id', 'cat_list', 'brand_id', 'brand_list', 'just_selected', 'keywords'));
        }
        $this->layout = false;
        return $this->show(compact('model_list', 'selected', 'simple'));
    }

    public function importAction(Request $request) {
        if ($request->isJson()) {
            GoodsRepository::importJson($request->get());
        }
        return $this->renderData();
    }
}