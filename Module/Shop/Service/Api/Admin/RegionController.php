<?php
namespace Module\Shop\Service\Api\Admin;

use Module\Shop\Domain\Models\RegionModel;

class RegionController extends Controller {

    public function indexAction($parent = 0, $keywords = '') {
        $model_list = RegionModel::where('parent_id', $parent)
            ->when(!empty($keywords), function ($query) {
            $query->where(function ($query) {
                RegionModel::search($query, 'name');
            });
        })->page();
        return $this->renderPage($model_list);
    }

    public function detailAction($id) {
        $model = RegionModel::find($id);
        return $this->render($model);
    }

    public function saveAction() {
        $model = new RegionModel();
        if (!$model->load() || !$model->autoIsNew()->save()) {
            return $this->renderFailure($model->getFirstError());
        }
        return $this->render($model);
    }

    public function deleteAction($id) {
        $next = [$id];
        while (!empty($next)) {
            $items = RegionModel::whereIn('parent_id', $next)->pluck('id');
            RegionModel::whereIn('parent_id', $next)->delete();
            $next = $items;
        }
        return $this->renderData(true);
    }

    public function treeAction() {
        return $this->renderData(RegionModel::cacheTree());
    }

    public function searchAction(string $keywords) {
        $model_list = RegionModel::when(!empty($keywords), function ($query) {
                RegionModel::searchWhere($query, 'name');
            })->page();
        return $this->renderPage($model_list);
    }
}