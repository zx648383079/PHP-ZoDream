<?php
namespace Module\Shop\Service\Admin;

use Module\Shop\Domain\Models\RegionModel;

class RegionController extends Controller {

    public function indexAction($parent = 0) {
        $model_list = RegionModel::where('parent_id', $parent)->page();
        return $this->show(compact('model_list'));
    }

    public function createAction($parent_id = 0) {
        return $this->editAction(0, $parent_id);
    }

    public function editAction($id, $parent_id = 0) {
        $model = RegionModel::findOrDefault($id, compact('parent_id'));
        return $this->show('edit', compact('model'));
    }

    public function saveAction() {
        $model = new RegionModel();
        if (!$model->load() || !$model->autoIsNew()->save()) {
            return $this->renderFailure($model->getFirstError());
        }
        return $this->renderData([
            'url' => $this->getUrl('region', ['parent' => $model->parent_id])
        ]);
    }

    public function deleteAction($id) {
        $next = [$id];
        while (!empty($next)) {
            $items = RegionModel::whereIn('parent_id', $next)->pluck('id');
            RegionModel::whereIn('parent_id', $next)->delete();
            $next = $items;
        }
        return $this->renderData([
            'url' => $this->getUrl('region')
        ]);
    }

    public function treeAction() {
        return $this->renderData(RegionModel::cacheTree());
    }
}