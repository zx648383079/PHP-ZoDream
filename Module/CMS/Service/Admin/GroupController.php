<?php
declare(strict_types=1);
namespace Module\CMS\Service\Admin;

use Module\CMS\Domain\Model\CategoryModel;
use Module\CMS\Domain\Model\GroupModel;
use Module\CMS\Domain\Model\ModelModel;

class GroupController extends Controller {
    public function indexAction(int $type = 0) {
        $model_list = GroupModel::where('type', $type)->page();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->editAction(0);
    }

    public function editAction(int $id) {
        $model = GroupModel::findOrNew($id);
        return $this->show('edit', compact('model'));
    }

    public function saveAction() {
        $model = new GroupModel();
        if (!$model->load() || !$model->autoIsNew()->save()) {
            return $this->renderFailure($model->getFirstError());
        }
        return $this->renderData([
            'url' => $this->getUrl('group', ['type' => $model->type])
        ]);
    }

    public function deleteAction(int $id) {
        $model = GroupModel::find($id);
        $model->delete();
        return $this->renderData([
            'url' => $this->getUrl('group', ['type' => $model->type])
        ]);
    }
}