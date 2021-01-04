<?php
declare(strict_types=1);
namespace Module\Finance\Service\Api;

use Module\Finance\Domain\Repositories\ProjectRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class ProjectController extends Controller {

    public function indexAction(int $id = 0) {
        if ($id > 0) {
            return $this->detailAction($id);
        }
        return $this->renderData(ProjectRepository::all());
    }

    public function detailAction(int $id) {
        try {
            $model = ProjectRepository::get($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function saveAction(Request $request) {
        try {
            $model = ProjectRepository::save($request->get());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function deleteAction(int $id) {
        ProjectRepository::remove($id);
        return $this->renderData(true);
    }

    public function earningsAction(int $id, float $money) {
        try {
            $model = ProjectRepository::earnings($id, $money);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

}