<?php
namespace Module\Shop\Service\Admin;


use Module\Shop\Domain\Model\BrandModel;
use Module\Shop\Domain\Model\CategoryModel;
use Module\Shop\Domain\Model\GoodsModel;

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
        return $this->runMethod('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = GoodsModel::findOrNew($id);
        $cat_list = CategoryModel::select('id', 'name')->all();
        $brand_list = BrandModel::select('id', 'name')->all();
        return $this->show(compact('model', 'cat_list', 'brand_list'));
    }

    public function saveAction() {
        $model = new GoodsModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->jsonSuccess([
                'url' => $this->getUrl('goods')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        GoodsModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('goods')
        ]);
    }
}