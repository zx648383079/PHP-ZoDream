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
            $model = ApiRepository::saveApi($input->get());
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
        return $this->show(
            MockRepository::request($input)
        );
    }

}