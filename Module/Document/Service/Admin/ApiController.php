<?php
namespace Module\Document\Service\Admin;

use Module\Document\Domain\Model\ApiModel;
use Module\Document\Domain\Model\FieldModel;
use Module\Document\Domain\Model\ProjectModel;
use Zodream\Helpers\Json;
use Zodream\Infrastructure\Http\Request;

class ApiController extends Controller {

    public function indexAction($id) {
        $api = ApiModel::find($id);
        $project = ProjectModel::find($api->project_id);
        $tree_list = ApiModel::getTree($api->project_id);
        return $this->show(compact('project', 'tree_list', 'api'));
    }

    public function createAction() {
        return $this->runMethod('edit', ['id' => null]);
    }

    public function editAction($id, $project_id = 0, $parent_id = 0) {
        $model = ApiModel::findOrNew($id);
        if ($project_id > 0) {
            $model->project_id = $project_id;
        }
        if ($parent_id > 0) {
            $model->parent_id = $parent_id;
        }
        $project = ProjectModel::find($model->project_id);
        $tree_list = ApiModel::getTree($model->project_id);
        $response_fields = FieldModel::where('method', FieldModel::KIND_RESPONSE)->where('api_id', $id)->all();
        $request_fields = FieldModel::where('method', FieldModel::KIND_REQUEST)->where('api_id', $id)->all();
        $header_fields = FieldModel::where('method', FieldModel::KIND_HEADER)->where('api_id', $id)->all();
        return $this->show(compact('model', 'project', 'tree_list', 'response_fields', 'request_fields', 'header_fields'));
    }

    public function saveAction() {
        $model = new ApiModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->jsonSuccess([
                'url' => $this->getUrl('api', ['id' => $model->id])
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function createFieldAction() {
        return $this->runMethod('editField', ['id' => null]);
    }

    public function editFieldAction($id) {
        $model = FieldModel::findOrNew($id);
        return $this->show(compact('model'));
    }

    public function deleteAction($id) {
        ApiModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('')
        ]);
    }
}