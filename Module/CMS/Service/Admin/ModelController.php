<?php
namespace Module\CMS\Service\Admin;

use Module\CMS\Domain\Model\ModelFieldModel;
use Module\CMS\Domain\Model\ModelModel;

class ModelController extends Controller {
    public function indexAction() {
        $model_list = ModelModel::all();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = ModelModel::findOrNew($id);
        if (!$model->position) {
            $model->position = 99;
        }
        return $this->show(compact('model'));
    }

    public function saveAction() {
        $model = new ModelModel();
        if (!$model->load() || !$model->autoIsNew()->save()) {
            return $this->jsonFailure($model->getFirstError());
        }
        return $this->jsonSuccess([
            'url' => $this->getUrl('model')
        ]);
    }

    public function deleteAction($id) {
        ModelModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('model')
        ]);
    }

    public function fieldAction($id) {
        $model = ModelModel::find($id);
        $model_list = ModelFieldModel::where('model_id', $id)->all();
        return $this->show(compact('model_list', 'model'));
    }

    public function createFieldAction() {
        return $this->runMethodNotProcess('editField', ['id' => null]);
    }

    public function editFieldAction($id, $model_id = null) {
        $model = ModelFieldModel::findOrNew($id);
        if (!$model->position) {
            $model->position = 99;
        }
        if (!$model->model_id) {
            $model->model_id = $model_id;
        }
        return $this->show(compact('model'));
    }

    public function saveFieldAction() {
        $model = new ModelFieldModel();
        if (!$model->load() || !$model->autoIsNew()->save()) {
            return $this->jsonFailure($model->getFirstError());
        }
        return $this->jsonSuccess([
            'url' => $this->getUrl('model/field', ['id' => $model->model_id])
        ]);
    }

    public function deleteFieldAction($id) {
        $model = ModelFieldModel::find($id);
        $model->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('model/field', ['id' => $model->model_id])
        ]);
    }

}