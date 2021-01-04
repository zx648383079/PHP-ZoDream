<?php
namespace Module\Document\Service\Admin;

use Module\Document\Domain\Model\PageModel;
use Module\Document\Domain\Model\ProjectModel;

class PageController extends Controller {

    public function indexAction($id) {
        $model = PageModel::find($id);
        $project = ProjectModel::find($model->project_id);
        $tree_list = PageModel::getTree($model->project_id);
        return $this->show(compact('project', 'tree_list', 'model'));
    }

    public function createAction($project_id = 0, $parent_id = 0) {
        return $this->editAction(0, $project_id, $parent_id);
    }

    public function editAction($id, $project_id = 0, $parent_id = 0) {
        $model = PageModel::findOrNew($id);
        if ($project_id > 0) {
            $model->project_id = $project_id;
        }
        if ($parent_id > 0) {
            $model->parent_id = $parent_id;
        }
        $project = ProjectModel::find($model->project_id);
        $tree_list = PageModel::getTree($model->project_id);
        return $this->show('edit', compact('model', 'project', 'tree_list'));
    }

    public function saveAction() {
        $model = new PageModel();
        if (!$model->load() || !$model->autoIsNew()->save()) {
            return $this->renderFailure($model->getFirstError());
        }
        if ($model->parent_id < 1) {
            return $this->renderData([
                'url' => $this->getUrl('project', ['id' => $model->project_id])
            ]);
        }
        return $this->renderData([
            'url' => $this->getUrl('page', ['id' => $model->id])
        ]);
    }


    public function deleteAction($id) {
        PageModel::where('id', $id)->delete();
        return $this->renderData([
            'url' => $this->getUrl('')
        ]);
    }
}