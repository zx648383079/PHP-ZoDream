<?php
namespace Module\Document\Service\Admin;

use Module\Document\Domain\Model\ApiModel;
use Module\Document\Domain\Model\FieldModel;
use Module\Document\Domain\Model\ProjectModel;
use Zodream\Html\Tree;
use Zodream\Http\Http;
use Zodream\Http\Uri;


class ApiController extends Controller {

    public function indexAction($id) {
        $api = ApiModel::find($id);
        $project = ProjectModel::find($api->project_id);
        $tree_list = ApiModel::getTree($api->project_id);
        $response_fields = FieldModel::where('kind', FieldModel::KIND_RESPONSE)->where('api_id', $id)->all();
        $request_fields = FieldModel::where('kind', FieldModel::KIND_REQUEST)->where('api_id', $id)->all();
        $header_fields = FieldModel::where('kind', FieldModel::KIND_HEADER)->where('api_id', $id)->all();
        $response_json = FieldModel::getDefaultData($id);
        $response_fields = (new Tree($response_fields))->makeTreeForHtml();
        return $this->show(compact('project', 'tree_list', 'api', 'header_fields', 'request_fields', 'response_fields', 'response_json'));
    }

    public function createAction($project_id = 0, $parent_id = 0) {
        $id = 0;
        return $this->runMethodNotProcess('edit', compact('id', 'project_id', 'parent_id'));
    }

    public function editAction($id, $project_id = 0, $parent_id = 0) {
        $model = ApiModel::findOrNew($id);
        $model->id = intval($model->id);
        if ($project_id > 0) {
            $model->project_id = $project_id;
        }
        if ($parent_id > 0) {
            $model->parent_id = $parent_id;
        }
        $project = ProjectModel::find($model->project_id);
        $tree_list = ApiModel::getTree($model->project_id);

        $response_fields = $this->getFieldList($id, FieldModel::KIND_RESPONSE);
        $request_fields = $this->getFieldList($id, FieldModel::KIND_REQUEST);
        $header_fields = $this->getFieldList($id, FieldModel::KIND_HEADER);
        $response_fields = (new Tree($response_fields))->makeTreeForHtml();
        return $this->show(compact('model', 'project', 'tree_list', 'response_fields', 'request_fields', 'header_fields'));
    }

    private function getFieldList($id, $kind) {
        $query = FieldModel::where('kind', $kind);
        if ($id > 0) {
            return $query->where('api_id', $id)->get();
        }
        return $query->whereIn('id', ApiModel::getStore())->get();
    }

    public function saveAction() {
        $id = intval(app('request')->get('id'));
        $model = new ApiModel();
        if (!$model->load() || !$model->autoIsNew()->save()) {
            return $this->jsonFailure($model->getFirstError());
        }
        if ($id < 1) {
            FieldModel::whereIn('id', ApiModel::clearStore())->update([
                'api_id' => $model->id
            ]);
        }
        if ($model->parent_id < 1) {
            return $this->jsonSuccess([
                'url' => $this->getUrl('project', ['id' => $model->project_id])
            ]);
        }
        return $this->jsonSuccess([
            'url' => $this->getUrl('api', ['id' => $model->id])
        ]);
    }


    public function deleteAction($id) {
        ApiModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('')
        ]);
    }

    public function debugAction($id) {
        $api = ApiModel::find($id);
        $project = ProjectModel::find($api->project_id);
        $tree_list = ApiModel::getTree($api->project_id);
        $request_fields = FieldModel::where('kind', FieldModel::KIND_REQUEST)->where('api_id', $id)->all();
        $header_fields = FieldModel::where('kind', FieldModel::KIND_HEADER)->where('api_id', $id)->all();
        return $this->show(compact('project', 'tree_list', 'api', 'header_fields', 'request_fields', 'response_fields'));
    }

    public function mockAction($id) {
        $response_json = FieldModel::getMockData($id);
        return $this->jsonSuccess($response_json);
    }

    public function debugResultAction() {
        $this->layout = false;
        $url = new Uri(app('request')->get('url'));
        $method = app('request')->get('method');
        $data = app('request')->get('request');
        $real_data = [];
        if (!empty($data) && isset($data['key'])) {
            foreach ($data['key'] as $i => $item) {
                $real_data[$item] = $data['value'][$i];
            }
        }
        $header = app('request')->get('header');
        $headers = [
            'request' => [],
            'response' => []
        ];
        $real_header = [];
        if (!empty($header) && isset($header['key'])) {
            foreach ($header['key'] as $i => $item) {
                $headers['request'][] = sprintf('%s: %s', $item, $header['value'][$i]);
                $real_header[$item] = $header['value'][$i];
            }
        }
        if ($method != 'POST') {
            $url->setData($real_data);
        }
        $http = new Http($url);
        $body = $http->header($header)
            ->maps($real_data)->method($method)->setHeaderOption(true)
            ->setOption(CURLOPT_RETURNTRANSFER, 1)
            ->setOption(CURLOPT_FOLLOWLOCATION, 1)
            ->setOption(CURLOPT_AUTOREFERER, 1)->getResponseText();
        $info = $http->getResponseHeader();
        $headers['response'] = explode(PHP_EOL, substr($body, 0, $info['header_size']));
        $body = substr($body, $info['header_size']);
        return $this->show(compact('body', 'headers', 'info'));
    }

    public function createFieldAction($kind = 0, $parent_id = 0, $api_id = 0) {
        $id = 0;
        return $this->runMethodNotProcess('editField', compact('id', 'kind', 'parent_id', 'api_id'));
    }

    public function editFieldAction($id, $kind = 0, $parent_id = 0, $api_id = 0) {
        $this->layout = false;
        $model = FieldModel::findOrNew($id);
        if (empty($id)) {
            $model->kind = $kind;
            $model->parent_id = $parent_id;
            $model->api_id = $api_id;
        }
        return $this->show(compact('model'));
    }

    public function saveFieldAction() {
        $model = new FieldModel();
        if (!$model->load() || !$model->autoIsNew()->save()) {
            return $this->refreshFieldAction($model->kind, $model->api_id);
        }
        if ($model->api_id < 1) {
            ApiModel::preStore($model->id);
        }
        return $this->refreshFieldAction($model->kind, $model->api_id);
    }

    public function deleteFieldAction($id) {
        $model = FieldModel::find($id);
        if (empty($model)) {
            return '';
        }
        $model->delete();
        FieldModel::where('parent_id', $id)->update([
            'parent_id' => 0
        ]);
        return $this->refreshFieldAction($model->kind, $model->api_id);
    }

    public function importFieldAction($content, $kind = 1, $api_id = 0) {
        $api_id = intval($api_id);
        $data = FieldModel::parseContent($content, $kind);
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

    public function refreshFieldAction($kind = 1, $api_id = 0) {
        $kind = intval($kind);
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