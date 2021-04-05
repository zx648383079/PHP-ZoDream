<?php
declare(strict_types=1);
namespace Module\CMS\Service\Admin;

use Module\CMS\Domain\Model\LinkageDataModel;
use Module\CMS\Domain\Model\LinkageModel;
use Zodream\Html\Tree;

class LinkageController extends Controller {
    public function indexAction() {
        $model_list = LinkageModel::all();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->editAction(0);
    }

    public function editAction(int $id) {
        $model = LinkageModel::findOrNew($id);
        return $this->show('edit', compact('model'));
    }

    public function saveAction() {
        $model = new LinkageModel();
        if (!$model->load() || !$model->autoIsNew()->save()) {
            return $this->renderFailure($model->getFirstError());
        }
        return $this->renderData([
            'url' => $this->getUrl('linkage')
        ]);
    }

    public function deleteAction(int $id) {
        LinkageModel::where('id', $id)->delete();
        return $this->renderData([
            'url' => $this->getUrl('linkage')
        ]);
    }

    public function dataAction(int $id, int $parent_id = 0) {
        $model = LinkageModel::find($id);
        $model_list = LinkageDataModel::where('linkage_id', $id)->where('parent_id', $parent_id)->all();
        $parent = $parent_id > 0 ? LinkageDataModel::find($id) : null;
        return $this->show(compact('model_list', 'model', 'parent_id', 'parent'));
    }

    public function createDataAction(int $linkage_id, int $parent_id = 0) {
        return $this->editDataAction(0, $linkage_id, $parent_id);
    }

    public function editDataAction(int $id, int $linkage_id = 0, int $parent_id = 0) {
        $model = LinkageDataModel::findOrNew($id);
        if (!$model->position) {
            $model->position = 99;
        }
        if (!$model->linkage_id) {
            $model->linkage_id = $linkage_id;
        }
        if (!$model->parent_id) {
            $model->parent_id = $parent_id;
        }
        return $this->show('editData', compact('model'));
    }

    public function saveDataAction() {
        $model = new LinkageDataModel();
        if (!$model->load() || !$model->autoIsNew()->createFullName()->save()) {
            return $this->renderFailure($model->getFirstError());
        }
        return $this->renderData([
            'url' => $this->getUrl('linkage/data', ['id' => $model->linkage_id, 'parent_id' => $model->parent_id])
        ]);
    }

    public function deleteDataAction(int $id) {
        $model = LinkageDataModel::find($id);
        $model->delete();
        return $this->renderData([
            'url' => $this->getUrl('linkage/data', ['id' => $model->linkage_id, 'parent_id' => $model->parent_id])
        ]);
    }

    public function treeAction(int $id) {
        return $this->renderData(LinkageModel::idTree($id));
    }

}