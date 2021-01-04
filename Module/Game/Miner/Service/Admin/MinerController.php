<?php
namespace Module\Game\Miner\Service\Admin;

use Module\Game\Miner\Domain\Model\MinerModel;

class MinerController extends Controller {

    public function indexAction() {
        $model_list = MinerModel::orderBy('id', 'desc')->page();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->editAction(0);
    }

    public function editAction($id) {
        $model = MinerModel::findOrNew($id);
        return $this->show('edit', compact('model'));
    }

    public function saveAction() {
        $model = new MinerModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->renderData([
                'url' => url('./@admin/miner')
            ]);
        }
        return $this->renderFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        MinerModel::where('id', $id)->delete();
        return $this->renderData([
            'url' => url('./@admin/miner')
        ]);
    }
}