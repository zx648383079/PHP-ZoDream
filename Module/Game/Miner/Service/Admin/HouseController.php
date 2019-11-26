<?php
namespace Module\Game\Miner\Service\Admin;

use Module\Game\Miner\Domain\Model\HouseModel;

class HouseController extends Controller {

    public function indexAction() {
        $model_list = HouseModel::orderBy('id', 'desc')->page();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => 0]);
    }

    public function editAction($id) {
        $model = HouseModel::findOrNew($id);
        return $this->show(compact('model'));
    }

    public function saveAction() {
        $model = new HouseModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->jsonSuccess([
                'url' => url('./@admin/house')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        HouseModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => url('./@admin/house')
        ]);
    }
}