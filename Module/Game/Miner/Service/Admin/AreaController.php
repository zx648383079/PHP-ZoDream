<?php
namespace Module\Game\Miner\Service\Admin;

use Module\Game\Miner\Domain\Model\AreaModel;

class AreaController extends Controller {

    public function indexAction() {
        $model_list = AreaModel::orderBy('id', 'desc')->page();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->editAction(0);
    }

    public function editAction($id) {
        $model = AreaModel::findOrNew($id);
        return $this->show('edit', compact('model'));
    }

    public function saveAction() {
        $model = new AreaModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->renderData([
                'url' => url('./@admin/area')
            ]);
        }
        return $this->renderFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        AreaModel::where('id', $id)->delete();
        return $this->renderData([
            'url' => url('./@admin/area')
        ]);
    }
}