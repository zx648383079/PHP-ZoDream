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
            $model = ProjectRepository::save($request->validate([
                'id' => 'int',
                'name' => 'required|string:0,35',
                'alias' => 'required|string:0,50',
                'money' => 'required',
                'account_id' => 'int',
                'earnings' => '',
                'start_at' => '',
                'end_at' => '',
                'earnings_number' => '',
                'product_id' => 'int',
                'status' => 'int:0,9',
                'deleted_at' => 'int',
                'color' => 'int:0,9',
                'remark' => '',
            ]));
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