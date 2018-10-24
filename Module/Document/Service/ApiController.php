<?php
namespace Module\Document\Service;

use Module\Document\Domain\Model\ApiModel;
use Module\Document\Domain\Model\FieldModel;
use Module\Document\Domain\Model\ProjectModel;

class ApiController extends Controller {

    public function indexAction($id) {
        $api = ApiModel::find($id);
        $project = ProjectModel::find($api->project_id);
        if (!$project->canRead()) {
            return $this->redirectWithMessage('./',
                '无权限查看此项目文档');
        }
        $tree_list = ApiModel::getTree($api->project_id);
        $response_fields = FieldModel::where('kind', FieldModel::KIND_RESPONSE)->where('api_id', $id)->all();
        $request_fields = FieldModel::where('kind', FieldModel::KIND_REQUEST)->where('api_id', $id)->all();
        $header_fields = FieldModel::where('kind', FieldModel::KIND_HEADER)->where('api_id', $id)->all();
        $response_json = FieldModel::getDefaultData($id);
        return $this->show(compact('project', 'tree_list', 'api', 'header_fields', 'request_fields', 'response_fields', 'response_json'));
    }


    public function mockAction($id) {
        $response_json = FieldModel::getMockData($id);
        return $this->jsonSuccess($response_json);
    }

}