<?php
declare(strict_types=1);
namespace Module\Document\Service\Api\Admin;

use Module\Document\Domain\Repositories\ApiRepository;
use Module\Document\Domain\Repositories\MockRepository;
use Module\Document\Domain\Repositories\ProjectRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class ApiController extends Controller {

    public function indexAction(int $id) {
        try {
            $model = ApiRepository::getSelf($id);
            ProjectRepository::getSelf($model->project_id);
            list($header, $request, $response) = ApiRepository::fieldList($id);
            $example = MockRepository::getDefaultData($id);
            return $this->render(array_merge(
                $model->toArray(),
                compact('header', 'request', 'response', 'example')
            ));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }


    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'name' => 'required|string:0,35',
                'type' => 'int:0,10',
                'method' => 'string:0,10',
                'uri' => 'string:0,255',
                'project_id' => 'required|int',
                'description' => 'string:0,255',
                'parent_id' => 'int',
                'version_id' => 'int',
                'request' => '',
                'response' => '',
                'header' => '',
            ]);
            $model = ApiRepository::saveApi($data);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }


    public function deleteAction(int $id) {
        try {
            $model = ApiRepository::getSelf($id);
            ProjectRepository::getSelf($model->project_id);
            ApiRepository::removeSelf($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function mockAction(int $id) {
        return $this->renderData(
            MockRepository::getMockData($id)
        );
    }

    public function debugResultAction(Input $input) {
        try {
            $data = $input->validate([
                'url' => 'required|string',
                'method' => 'required|string',
                'type' => '',
                'raw_type' => '',
                'header' => '',
                'body' => '',
            ]);
            return $this->renderData(
                MockRepository::request($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function parseAction(string $content, int $kind = 1) {
        return $this->renderData(
            MockRepository::parseContent($content, $kind)
        );
    }

}