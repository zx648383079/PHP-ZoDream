<?php
namespace Module\Document\Service;

use Module\Document\Domain\CodeParser;
use Module\Document\Domain\Model\FieldModel;
use Module\Document\Domain\Repositories\ApiRepository;
use Module\Document\Domain\Repositories\MockRepository;
use Module\Document\Domain\Repositories\ProjectRepository;

class ApiController extends Controller {

    public function indexAction(int $id) {
        $api = ApiRepository::get($id);
        $project = ProjectRepository::get($api->project_id);
        if (!$project->canRead()) {
            return $this->redirectWithMessage('./',
                '无权限查看此项目文档');
        }
        $tree_list = ApiRepository::tree($api->project_id, $api->version_id);
        list($header_fields, $request_fields, $response_fields) = ApiRepository::fieldList($id);
        $response_json = MockRepository::getDefaultData($id);
        $languages = (new CodeParser())->getLanguages();
        return $this->show(compact('project', 'tree_list', 'api',
            'header_fields', 'request_fields', 'response_fields', 'response_json', 'languages'));
    }


    public function mockAction(int $id) {
        $response_json = MockRepository::getMockData($id);
        return $this->renderData($response_json);
    }

    public function codeAction(int $id, string $lang, int $kind = FieldModel::KIND_RESPONSE) {
        $coder = new CodeParser();
        if ($kind < 1) {
            $content = $coder->formatHttp($id, $lang);
        } else {
            $content = $coder->formatField($id, $kind, '', $lang);
        }
        return $this->renderData($content);
    }

}