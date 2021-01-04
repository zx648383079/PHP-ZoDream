<?php
namespace Module\Shop\Service\Api\Admin;


use Module\Shop\Domain\Models\Advertisement\AdModel;
use Module\Shop\Domain\Models\Advertisement\AdPageModel;
use Module\Shop\Domain\Models\Advertisement\AdPositionModel;

class AdController extends Controller {

    public function indexAction($keywords = null, $position_id = 0) {
        $model_list = AdPageModel::with('position')
            ->when(!empty($keywords), function ($query) {
                $query->where(function ($query) {
                    AdModel::search($query, 'name');
                });
            })->when(!empty($position_id), function ($query) use ($position_id) {
                $query->where('position_id', intval($position_id));
            })->page();
        return $this->renderPage($model_list);
    }


    public function detailAction($id) {
        $model = AdModel::find($id);
        return $this->render($model);
    }

    public function saveAction() {
        $model = new AdModel();
        $model->load();
        if ($model->type % 2 > 0) {
            $model->content = request()->get('content_url');
        }
        if ($model->autoIsNew()->save()) {
            return $this->render($model);
        }
        return $this->renderFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        AdModel::where('id', $id)->delete();
        return $this->renderData(true);
    }


    public function positionAction() {
        $model_list = AdPositionModel::query()->page();
        return $this->renderPage($model_list);
    }

    public function detailPositionAction($id) {
        $model = AdPositionModel::find($id);
        return $this->render($model);
    }

    public function savePositionAction() {
        $model = new AdPositionModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->render($model);
        }
        return $this->renderFailure($model->getFirstError());
    }

    public function deletePositionAction($id) {
        AdPositionModel::where('id', $id)->delete();
        return $this->renderData(true);
    }

    public function positionAllAction() {
        $model_list = AdPositionModel::query()->get('id', 'name');
        return $this->renderData($model_list);
    }
}