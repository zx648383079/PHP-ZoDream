<?php
declare(strict_types=1);
namespace Module\Task\Service\Api;

use Module\Task\Domain\Repositories\PlanRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class PlanController extends Controller {

    public function rules() {
        return ['*' => '@'];
    }


    public function indexAction(int $type) {
        return $this->renderPage(
            PlanRepository::getList($type)
        );
    }

    public function saveAction(Request $request) {
        try {
            $data = $request->validate([
                'id' => 'int',
                'task_id' => 'required|int',
                'plan_type' => 'int:0,127',
                'plan_time' => 'required',
                'amount' => 'int:0,127',
                'priority' => 'int:0,127',
            ]);
            $model = PlanRepository::save($data);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function deleteAction(int $id) {
        try {
            PlanRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}