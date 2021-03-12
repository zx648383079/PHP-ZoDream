<?php
namespace Module\Document\Service\Admin;

use Module\Document\Domain\Model\ApiModel;
use Module\Document\Domain\Model\FieldModel;
use Module\Document\Domain\Model\ProjectModel;
use Module\Document\Domain\Repositories\ApiRepository;
use Module\Document\Domain\Repositories\MockRepository;
use Module\Document\Domain\Repositories\ProjectRepository;
use Zodream\Html\Tree;
use Zodream\Http\Http;
use Zodream\Http\Uri;
use Zodream\Infrastructure\Contracts\Http\Input;


class ApiController extends Controller {

    public function indexAction(int $id) {
        try {
            $api = ApiRepository::getSelf($id);
            $project = ProjectRepository::getSelf($api->project_id);
            $tree_list = ApiRepository::tree($api->project_id, $api->version_id);
            list($header_fields, $request_fields, $response_fields) = ApiRepository::fieldList($id);
            $response_json = MockRepository::getDefaultData($id);
            return $this->show(compact('project', 'tree_list', 'api', 'header_fields', 'request_fields', 'response_fields', 'response_json'));
        } catch (\Exception $ex) {
            return $this->redirectWithMessage($this->getUrl(''), $ex->getMessage());
        }
    }

    public function createAction(int $project_id = 0, int $parent_id = 0, int $version_id = 0) {
        return $this->editAction(0, $project_id, $parent_id, $version_id);
    }

    public function editAction(int $id, int $project_id = 0, int $parent_id = 0, int $version_id = 0) {
        $model = $id > 0 ? ApiRepository::getSelf($id) : new ApiModel(
            compact('project_id', 'parent_id', 'version_id')
        );
        $project = ProjectRepository::getSelf($model->project_id);
        $tree_list = ApiRepository::tree($model->project_id, intval($model->version_id));

        $response_fields = $this->getFieldList($id, FieldModel::KIND_RESPONSE);
        $request_fields = $this->getFieldList($id, FieldModel::KIND_REQUEST);
        $header_fields = $this->getFieldList($id, FieldModel::KIND_HEADER);
        $response_fields = (new Tree($response_fields))->makeTreeForHtml();
        return $this->show('edit', compact('model', 'project', 'tree_list', 'response_fields', 'request_fields', 'header_fields'));
    }

    private function getFieldList($id, $kind) {
        $query = FieldModel::where('kind', $kind);
        if ($id > 0) {
            return $query->where('api_id', $id)->get();
        }
        return $query->whereIn('id', ApiModel::getStore())->get();
    }

    public function saveAction(Input $input) {
        try {
            $model = ApiRepository::saveWeb($input->get());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        if ($model->parent_id < 1) {
            return $this->renderData([
                'url' => $this->getUrl('project', [
                    'id' => $model->project_id,
                    'version' => $model->version_id
                ])
            ]);
        }
        return $this->renderData([
            'url' => $this->getUrl('api', ['id' => $model->id])
        ]);
    }


    public function deleteAction(int $id) {
        try {
            $model = ApiRepository::getSelf($id);
            ProjectRepository::getSelf($model->project_id);
            ApiRepository::removeSelf($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => $this->getUrl('project', [
                'id' => $model->project_id,
                'version' => $model->version_id
            ])
        ]);
    }

    public function debugAction(int $id) {
        $api = ApiRepository::getSelf($id);
        $project = ProjectRepository::getSelf($api->project_id);
        $tree_list = ApiRepository::tree($api->project_id, $api->version_id);
        list($header_fields, $request_fields, $_) = ApiRepository::fieldList($id);
        return $this->show(compact('project', 'tree_list', 'api', 'header_fields', 'request_fields', 'response_fields'));
    }

    public function mockAction(int $id) {
        $response_json = MockRepository::getMockData($id);
        return $this->renderData($response_json);
    }

    public function debugResultAction(Input $input) {
        $this->layout = false;
        return $this->show(
            MockRepository::request($input)
        );
    }

    public function createFieldAction(int $kind = 0, int $parent_id = 0, int $api_id = 0) {
        return $this->editFieldAction(0, $kind, $parent_id, $api_id);
    }

    public function editFieldAction(int $id, int $kind = 0, int $parent_id = 0, int $api_id = 0) {
        $this->layout = false;
        $model = $id > 0 ? ApiRepository::fieldSelf($id) :
            new FieldModel(compact('kind',  'parent_id', 'api_id'));
        return $this->show('editField', compact('model'));
    }

    public function saveFieldAction(Input $input) {
        try {
            $model = ApiRepository::fieldSave($input->get());
        } catch (\Exception $ex) {
            return $this->refreshFieldAction($input->get('kind'),
                $input->get('api_id'));
        }
        if ($model->api_id < 1) {
            ApiModel::preStore($model->id);
        }
        return $this->refreshFieldAction($model->kind, $model->api_id);
    }

    public function deleteFieldAction(int $id) {
        $model = ApiRepository::fieldSelf($id);
        if (empty($model)) {
            return '';
        }
        ApiRepository::fieldRemove($id);
        return $this->refreshFieldAction($model->kind, $model->api_id);
    }

    public function importFieldAction(string $content, int $kind = 1, int $api_id = 0) {
        $api_id = intval($api_id);
        $data = MockRepository::parseContent($content, $kind);
        foreach ($data as $model) {
            $model->api_id = $api_id;
            if (!$model->check(ApiModel::getStore())) {
                continue;
            }
            $model->save();
            if ($api_id < 1) {
                ApiModel::preStore($model);
            }
        }
        return $this->refreshFieldAction($kind, $api_id);
    }

    public function refreshFieldAction(int $kind = 1, int $api_id = 0) {
        $this->layout = false;
        if ($kind === FieldModel::KIND_RESPONSE) {
            $response_fields = $this->getFieldList($api_id, FieldModel::KIND_RESPONSE);
            $response_fields = (new Tree($response_fields))->makeTreeForHtml();
            return $this->show('responseRow', compact('response_fields'));
        }
        if ($kind === FieldModel::KIND_REQUEST) {
            $request_fields = $this->getFieldList($api_id, FieldModel::KIND_REQUEST);
            return $this->show('requestRow', compact('request_fields'));
        }
        if ($kind == FieldModel::KIND_HEADER) {
            $header_fields = $this->getFieldList($api_id, FieldModel::KIND_HEADER);
            return $this->show('headerRow', compact('header_fields'));
        }
        return '';
    }
}