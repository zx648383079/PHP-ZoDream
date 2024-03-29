<?php
namespace Module\Shop\Service\Admin;

use Module\Shop\Domain\Models\ShippingGroupModel;
use Module\Shop\Domain\Models\ShippingModel;
use Module\Shop\Domain\Models\ShippingRegionModel;
use Module\Shop\Domain\Repositories\Admin\ShippingRepository;

class ShippingController extends Controller {

    public function indexAction() {
        $model_list = ShippingModel::page();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->editAction(0);
    }

    public function editAction($id) {
        $model = ShippingModel::findOrDefault($id, ['position' => 60]);
        $shipping_list = ShippingRepository::getPlugins();
        $group_list = [];
        if (!$model->isNewRecord) {
            $group_list = ShippingGroupModel::where('shipping_id', $model->id)
                ->get();
        }
        return $this->show('edit', compact('model', 'shipping_list', 'group_list'));
    }

    public function saveAction() {
        $model = new ShippingModel();
        if (!$model->load() || !$model->autoIsNew()->save()) {
            return $this->renderFailure($model->getFirstError());
        }
        ShippingGroupModel::batchSave($model->id, request()->get('shipping'));
        return $this->renderData([
            'url' => $this->getUrl('shipping')
        ]);
    }

    public function deleteAction(int $id) {
        ShippingRepository::remove($id);
        return $this->renderData([
            'url' => $this->getUrl('shipping')
        ]);
    }
}