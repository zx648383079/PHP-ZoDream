<?php
namespace Module\Shop\Service\Api\Admin;

use Domain\Model\SearchModel;
use Module\Shop\Domain\Models\RegionModel;

class RegionController extends Controller {

    public function indexAction(int $parent = 0, string $keywords = '') {
        $model_list = RegionModel::where('parent_id', $parent)
            ->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, 'name');
        })->page();
        return $this->renderPage($model_list);
    }

    public function detailAction(int $id) {
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

    public function deleteAction(int $id) {
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
}