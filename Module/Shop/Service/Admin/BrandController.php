<?php
namespace Module\Shop\Service\Admin;


use Module\Shop\Domain\Models\BrandModel;
use Module\Shop\Domain\Models\GoodsModel;
use Zodream\Database\Command;

class BrandController extends Controller {

    public function indexAction() {
        $model_list = BrandModel::page();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->editAction(0);
    }

    public function editAction($id) {
        $model = BrandModel::findOrNew($id);
        return $this->show('edit', compact('model'));
    }

    public function saveAction() {
        $model = new BrandModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->renderData([
                'url' => $this->getUrl('brand')
            ]);
        }
        return $this->renderFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        BrandModel::where('id', $id)->delete();
        return $this->renderData([
            'url' => $this->getUrl('brand')
        ]);
    }

    public function refreshAction() {
        BrandModel::refreshPk(function ($old_id, $new_id) {
            GoodsModel::where('brand_id', $old_id)->update([
                'brand_id' => $new_id
            ]);
        });
        return $this->renderData([
            'refresh' => true
        ]);
    }

    public function dialogAction() {
        $model_list = BrandModel::page();
        return $this->show(compact('model_list'));
    }
}