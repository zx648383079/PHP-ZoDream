<?php
declare(strict_types=1);
namespace Module\Finance\Service\Api;

use Module\Finance\Domain\Repositories\BudgetRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class BudgetController extends Controller {

    public function indexAction() {
        $model_list = BudgetRepository::getList();
        return $this->renderPage($model_list);
    }

    public function detailAction(int $id) {
        try {
            $model = BudgetRepository::get($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function saveAction(Request $request) {
        try {
            $model = BudgetRepository::save($request->get());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function deleteAction(int $id) {
        BudgetRepository::softDelete($id);
        return $this->renderData(true);
    }

    public function refreshAction() {
        BudgetRepository::refreshSpent();
        return $this->renderData(true);
    }

    public function statisticsAction(int $id) {
        try {
            $model = BudgetRepository::statistics($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }
}