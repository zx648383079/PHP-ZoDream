<?php
namespace Module\Shop\Service\Admin;


use Module\Shop\Domain\Model\AttributeGroupModel;
use Module\Shop\Domain\Model\AttributeModel;
use Module\Shop\Domain\Model\BrandModel;
use Module\Shop\Domain\Model\CategoryModel;
use Module\Shop\Domain\Model\GoodsAttributeModel;
use Module\Shop\Domain\Model\GoodsModel;
use Module\Shop\Domain\Model\ProductModel;
use Zodream\Helpers\Json;

class GoodsController extends Controller {

    public function indexAction($keywords = null, $cat_id = 0, $brand_id = 0) {
        $model_list = GoodsModel::with('category', 'brand')
            ->when(!empty($keywords), function ($query) {
                $query->where(function ($query) {
                    GoodsModel::search($query, 'name');
                });
            })->when(!empty($cat_id), function ($query) use ($cat_id) {
                $query->where('cat_id', intval($cat_id));
            })->when(!empty($brand_id), function ($query) use ($brand_id) {
                $query->where('brand_id', intval($brand_id));
            })->page();
        $cat_list = CategoryModel::select('id', 'name')->all();
        $brand_list = BrandModel::select('id', 'name')->all();
        return $this->show(compact('model_list', 'cat_list', 'brand_list'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = GoodsModel::findOrNew($id);
        $cat_list = CategoryModel::select('id', 'name')->all();
        $brand_list = BrandModel::select('id', 'name')->all();
        $group_list = AttributeGroupModel::all();
        return $this->show(compact('model', 'cat_list', 'brand_list', 'group_list'));
    }

    public function saveAction($id, $product = null) {
        $model = new GoodsModel();
        if (!$model->load() || !$model->autoIsNew()->save()) {
            return $this->jsonFailure($model->getFirstError());
        }
        if ($id < 1) {
            GoodsAttributeModel::where('goods_id', '<', 1)->update([
                'goods_id' => $id
            ]);
        }
        if (!empty($product)) {
            ProductModel::batchSave(is_array($product) ? $product : Json::decode($product), $model->id);
        }
        return $this->jsonSuccess([
            'url' => $this->getUrl('goods')
        ]);
    }

    public function deleteAction($id) {
        GoodsModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('goods')
        ]);
    }

    public function attributeAction($group_id, $goods_id = 0) {
        $attr_list = AttributeModel::where('group_id', $group_id)->orderBy('position asc')->orderBy('type asc')->asArray()->all();
        foreach ($attr_list as &$item) {
            $item['default_value'] = empty($item['default_value']) || $item['input_type'] < 1 ? [] : explode(PHP_EOL, $item['default_value']);
            $item['attr_items'] = GoodsAttributeModel::where('goods_id', $goods_id)->where('attribute_id', $item['id'])->all();
        }
        unset($item);
        $product_list = ProductModel::where('goods_id', $goods_id)->orderBy('id asc')->all();
        return $this->jsonSuccess(compact('attr_list', 'product_list'));
    }

    public function saveAttributeAction() {
        $model = new GoodsAttributeModel();
        if (!$model->load()) {
            return $this->jsonFailure($model->getFirstError());
        }
        $model->autoIsNew();
        if (!$model->checkValue()) {
            return $this->jsonFailure('属性值已存在！');
        }
        if (!$model->save()) {
            return $this->jsonFailure($model->getFirstError());
        }
        return $this->jsonSuccess($model->toArray());
    }

    public function editAttributeAction($attr, $goods_id = 0) {
        $models = GoodsAttributeModel::batchSave($attr, $goods_id);
        if (empty($models)) {
            return $this->jsonFailure('更新失败！');
        }
        return $this->jsonSuccess(count($models) == 1 ? reset($models) : $models);
    }

    public function deleteAttributeAction($attribute_id = 0, $value = 0, $goods_id = 0, $id = 0) {
        if ($id > 0) {
            GoodsAttributeModel::where('id', $id)->delete();
        } else {
            GoodsAttributeModel::where('goods_id', $goods_id)->where('attribute_id', $attribute_id)->where('value', $value)->delete();
        }
        return $this->jsonSuccess();
    }
}