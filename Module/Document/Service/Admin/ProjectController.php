<?php
namespace Module\Document\Service\Admin;

use Module\Document\Domain\Model\ApiModel;
use Module\Document\Domain\Model\ProjectModel;
use Zodream\Helpers\Json;


class ProjectController extends Controller {

    public function indexAction($id) {
        $project = ProjectModel::findWithAuth($id);
        if (empty($project)) {
            return $this->redirect($this->getUrl(''));
        }
        $tree_list = ApiModel::getTree($id);
        return $this->show(compact('project', 'tree_list'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = ProjectModel::findOrNew($id);
        $project_list = ProjectModel::select('name', 'id')->all();
        return $this->show(compact('model', 'project_list'));
    }

    public function saveAction() {
        $model = new ProjectModel();
        $model->user_id = auth()->id();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->jsonSuccess([
                'url' => $this->getUrl('project', ['id' => $model->id])
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        ProjectModel::where('id', $id)->where('user_id', auth()->id())->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('')
        ]);
    }
}