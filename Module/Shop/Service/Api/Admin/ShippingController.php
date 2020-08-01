<?php
namespace Module\Shop\Service\Api\Admin;

use Module\Shop\Domain\Models\ShippingGroupModel;
use Module\Shop\Domain\Models\ShippingModel;
use Module\Shop\Domain\Models\ShippingRegionModel;
use Module\Shop\Domain\Repositories\ShippingRepository;

class ShippingController extends Controller {

    public function indexAction() {
        $model_list = ShippingModel::page();
        return $this->renderPage($model_list);
    }

    public function pluginAction() {
        $items = ShippingRepository::getPlugins();
        $data = [];
        foreach ($items as $value => $name) {
            $data[] = compact('name', 'value');
        }
        return $this->renderData($data);
    }

    public function detailAction($id) {
        $model = ShippingModel::find($id);
        return $this->render($model);
    }

    public function saveAction() {
        $model = new ShippingModel();
        if (!$model->load() || !$model->autoIsNew()->save()) {
            return $this->renderFailure($model->getFirstError());
        }
        ShippingGroupModel::batchSave($model->id, app('request')->get('shipping'));
        return $this->render($model);
    }

    public function deleteAction($id) {
        ShippingModel::where('id', $id)->delete();
        ShippingGroupModel::where('shipping_id', $id)->delete();
        ShippingRegionModel::where('shipping_id', $id)->delete();
        return $this->renderData(true);
    }
}