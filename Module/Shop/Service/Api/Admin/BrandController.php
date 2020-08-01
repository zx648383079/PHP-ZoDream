<?php
namespace Module\Shop\Service\Api\Admin;


use Module\Shop\Domain\Models\BrandModel;
use Module\Shop\Domain\Models\GoodsModel;
use Zodream\Database\Command;

class BrandController extends Controller {

    public function indexAction() {
        $model_list = BrandModel::page();
        return $this->renderPage($model_list);
    }

    public function detailAction($id) {
        $model = BrandModel::find($id);
        return $this->render($model);
    }

    public function saveAction() {
        $model = new BrandModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->render($model);
        }
        return $this->renderFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        BrandModel::where('id', $id)->delete();
        return $this->renderData(true);
    }

    public function refreshAction() {
        BrandModel::refreshPk(function ($old_id, $new_id) {
            GoodsModel::where('brand_id', $old_id)->update([
                'brand_id' => $new_id
            ]);
        });
        return $this->renderData(true);
    }
}