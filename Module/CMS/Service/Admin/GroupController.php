<?php
namespace Module\CMS\Service\Admin;

use Module\CMS\Domain\Model\CategoryModel;
use Module\CMS\Domain\Model\GroupModel;
use Module\CMS\Domain\Model\ModelModel;

class GroupController extends Controller {
    public function indexAction($type = 0) {
        $model_list = GroupModel::where('type', $type)->page();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = GroupModel::findOrNew($id);
        return $this->show(compact('model'));
    }

    public function saveAction($id) {
        $model = new GroupModel();
        if (!$model->load() || !$model->autoIsNew()->save()) {
            return $this->jsonFailure($model->getFirstError());
        }
        return $this->jsonSuccess([
            'url' => $this->getUrl('group', ['type' => $model->type])
        ]);
    }

    public function deleteAction($id) {
        $model = GroupModel::find($id);
        $model->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('group', ['type' => $model->type])
        ]);
    }
}