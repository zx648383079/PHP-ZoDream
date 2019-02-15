<?php
namespace Module\Shop\Service\Admin;


use Module\Shop\Domain\Model\Advertisement\AdModel;
use Module\Shop\Domain\Model\Advertisement\AdPositionModel;

class AdController extends Controller {

    public function indexAction($keywords = null, $position_id = 0) {
        $model_list = AdModel::with('position')
            ->when(!empty($keywords), function ($query) {
                $query->where(function ($query) {
                    AdModel::search($query, 'name');
                });
            })->when(!empty($position_id), function ($query) use ($position_id) {
                $query->where('position_id', intval($position_id));
            })->page();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = AdModel::findOrNew($id);
        $position_list = AdPositionModel::all();
        return $this->show(compact('model', 'position_list'));
    }

    public function saveAction() {
        $model = new AdModel();
        $model->load();
        if ($model->type % 2 > 0) {
            $model->content = app('request')->get('content_url');
        }
        if ($model->autoIsNew()->save()) {
            return $this->jsonSuccess([
                'url' => $this->getUrl('ad')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }


    public function positionAction() {
        $model_list = AdPositionModel::all();
        return $this->show(compact('model_list'));
    }

    public function createPositionAction() {
        return $this->runMethodNotProcess('editPosition', ['id' => null]);
    }

    public function editPositionAction($id) {
        $model = AdPositionModel::findOrNew($id);
        return $this->show(compact('model'));
    }

    public function savePositionAction() {
        $model = new AdPositionModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->jsonSuccess([
                'url' => $this->getUrl('ad/position')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deletePositionAction($id) {
        AdPositionModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('ad/positionad/position')
        ]);
    }
}