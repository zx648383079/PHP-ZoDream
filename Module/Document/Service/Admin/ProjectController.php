<?php
namespace Module\Document\Service\Admin;

use Module\Document\Domain\Model\ApiModel;
use Module\Document\Domain\Model\ProjectModel;
use Zodream\Helpers\Json;
use Zodream\Infrastructure\Http\Request;

class ProjectController extends Controller {

    public function indexAction($id) {
        $project = ProjectModel::find($id);
        $tree_list = ApiModel::getTree($id);
        return $this->show(compact('project', 'tree_list'));
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
        $id = intval(Request::post('id'));
        if (!empty($id)) {
            $model->id = $id;
            $model->isNewRecord = false;
        }
        $model->name = Request::post('name');
        $model->description = Request::post('description');
        $data = Request::post('environment');
        $env = [];
        foreach ($data['name'] as $key => $item) {
            if (empty($item)) {
                continue;
            }
            $env[] = [
                'name' => $item,
                'title' => $data['title'][$key],
                'domain' => $data['domain'][$key]
            ];
        }
        $model->environment = Json::encode($env);
        if ($model->save()) {
            return $this->jsonSuccess([
                'url' => $this->getUrl('project', ['id' => $model->id])
            ]);
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