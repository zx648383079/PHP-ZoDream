<?php
namespace Module\CMS\Service\Admin;

class CategoryController extends Controller {
    public function indexAction() {

        return $this->show();
    }

    public function createAction() {
        return $this->runMethod('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = ProjectModel::findOrNew($id);
        $project_list = ProjectModel::select('name', 'id')->all();
        return $this->show(compact('model', 'project_list'));
    }

    public function saveAction() {
        $model = new ProjectModel();
        if (!$model->load() || !$model->autoIsNew()->setMock()->save()) {
            return $this->jsonFailure($model->getFirstError());
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        ProjectModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('')
        ]);
    }
}