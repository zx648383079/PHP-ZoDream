<?php
namespace Module\Document\Service\Admin;

use Module\Document\Domain\Model\ApiModel;
use Module\Document\Domain\Model\ProjectModel;
use Zodream\Helpers\Json;


class ProjectController extends Controller {

    public function indexAction($id) {
        $project = ProjectModel::find($id);
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
        $id = intval(app('request')->get('id'));
        if (!empty($id)) {
            $model->id = $id;
            $model->isNewRecord = false;
        }
        $model->name = app('request')->get('name');
        $model->description = app('request')->get('description');
        $data = app('request')->get('environment');
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
        if (empty($env)) {
            return $this->jsonFailure('请至少填写一个环境域名');
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