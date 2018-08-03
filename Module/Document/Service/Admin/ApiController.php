<?php
namespace Module\Document\Service\Admin;

use Module\Document\Domain\Model\ApiModel;
use Module\Document\Domain\Model\FieldModel;
use Module\Document\Domain\Model\ProjectModel;
use Zodream\Helpers\Json;
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
        return $this->show(compact('project', 'tree_list', 'api', 'header_fields', 'request_fields', 'response_fields', 'response_json'));
    }

    public function createAction($project_id = 0, $parent_id = 0) {
        $id = 0;
        return $this->runMethodNotProcess('edit', compact('id', 'project_id', 'parent_id'));
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
        $response_fields = FieldModel::where('kind', FieldModel::KIND_RESPONSE)->where('api_id', $id)->all();
        $request_fields = FieldModel::where('kind', FieldModel::KIND_REQUEST)->where('api_id', $id)->all();
        $header_fields = FieldModel::where('kind', FieldModel::KIND_HEADER)->where('api_id', $id)->all();
        return $this->show(compact('model', 'project', 'tree_list', 'response_fields', 'request_fields', 'header_fields'));
    }

    public function saveAction() {
        $id = intval(app('request')->get('id'));
        $model = new ApiModel();
        if (!$model->load() || !$model->autoIsNew()->save()) {
            return $this->jsonFailure($model->getFirstError());
        }
        if ($id < 1) {
            FieldModel::where('api_id', $id)->update([
                'api_id' => $model->id
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
        $url = new Uri(app('request')->get('url'));
        $method = app('request')->get('method');
        $data = app('request')->get('request');
        $real_data = [];
        foreach ($data['key'] as $i => $item) {
            $real_data[$item] = $data['value'][$i];
        }
        $header = app('request')->get('header');
        $headers = [
            'request' => [],
            'response' => []
        ];
        $real_header = [];
        foreach ($header['key'] as $i => $item) {
            $headers['request'][] = sprintf('%s: %s', $item, $header['value'][$i]);
            $real_header[$item] = $header['value'][$i];
        }
        if ($method != 'POST') {
            $url->setData($data);
        }
        $http = new Http($url);
        $body = $http->header($header)
            ->maps($data)->method($method)->setHeaderOption(true)
            ->setOption(CURLOPT_RETURNTRANSFER, 1)
            ->setOption(CURLOPT_FOLLOWLOCATION, 1)
            ->setOption(CURLOPT_AUTOREFERER, 1)->getResponseText();
        $info = $http->getResponseHeader();
        $headers['response'] = explode(PHP_EOL, substr($body, 0, $info['header_size']));
        $body = substr($body, $info['header_size']);
        return $this->show(compact('body', 'headers', 'info'));
    }

    public function createFieldAction() {
        return $this->runMethodNotProcess('editField', ['id' => null]);
    }

    public function editFieldAction($id, $kind = 0, $parent_id = 0, $api_id = 0) {
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
        if (!$model->load() || !$model->autoIsNew()->setMock()->save()) {
            return $this->jsonFailure($model->getFirstError());

        }
        $data = $model->toArray();
        $data['edit_url'] = $this->getUrl('api/edit_field', ['id' => $model->id]);
        $data['delete_url'] = $this->getUrl('api/delete_field', ['id' => $model->id]);
        $data['has_children'] = $model->has_children;
        if ($data['has_children']) {
            $data['child_url'] = $this->getUrl('api/create_field', ['parent_id' => $model->id, 'api_id' => $model->api_id, 'kind' => $model->kind]);
        }
        $data['type_label'] = $model->type_label;
        return $this->jsonSuccess($data);
    }

    public function deleteFieldAction($id) {
        FieldModel::where('id', $id)->delete();
        return $this->jsonSuccess();
    }


}