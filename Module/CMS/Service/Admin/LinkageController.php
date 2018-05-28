<?php
namespace Module\CMS\Service\Admin;

use Module\CMS\Domain\Model\LinkageDataModel;
use Module\CMS\Domain\Model\LinkageModel;

class LinkageController extends Controller {
    public function indexAction() {
        $model_list = LinkageModel::all();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->runMethod('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = LinkageModel::findOrNew($id);
        return $this->show(compact('model'));
    }

    public function saveAction() {
        $model = new LinkageModel();
        if (!$model->load() || !$model->autoIsNew()->save()) {
            return $this->jsonFailure($model->getFirstError());
        }
        return $this->jsonSuccess([
            'url' => $this->getUrl('linkage')
        ]);
    }

    public function deleteAction($id) {
        LinkageModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('model')
        ]);
    }

    public function dataAction($id, $parent_id = 0) {
        $model = LinkageModel::find($id);
        $model_list = LinkageDataModel::where('model_id', $id)->where('parent_id', $parent_id)->all();
        return $this->show(compact('model_list', 'model', 'parent_id'));
    }

    public function createFieldAction() {
        return $this->runMethod('editField', ['id' => null]);
    }

    public function editFieldAction($id, $linkage_id = null, $parent_id = null) {
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
        return $this->show(compact('model'));
    }

    public function saveFieldAction() {
        $model = new LinkageDataModel();
        if (!$model->load() || !$model->autoIsNew()->save()) {
            return $this->jsonFailure($model->getFirstError());
        }
        return $this->jsonSuccess([
            'url' => $this->getUrl('model/data', ['id' => $model->linkage_id, 'parent_id' => $model->parent_id])
        ]);
    }

    public function deleteFieldAction($id) {
        $model = LinkageDataModel::find($id);
        $model->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('model/field', ['id' => $model->linkage_id, 'parent_id' => $model->parent_id])
        ]);
    }

}