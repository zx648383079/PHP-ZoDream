<?php
namespace Module\Finance\Service\Api;

use Module\Finance\Domain\Model\BudgetModel;
use Module\Finance\Domain\Repositories\BudgetRepository;
use Zodream\Route\Controller\RestController;


class BudgetController extends RestController {

    protected function rules() {
        return ['*' => '@'];
    }

    public function indexAction() {
        $model_list = BudgetRepository::getList();
        return $this->renderPage($model_list);
    }

    public function infoAction($id) {
        $model = BudgetModel::findOrNew($id);
        return $this->render($model);
    }

    public function saveAction() {
        $model = new BudgetModel();
        if ($model->load() && $model->set('user_id', auth()->id())
                ->autoIsNew()->save()) {
            $model->refreshSpent();
            return $this->render($model);
        }
        return $this->renderFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        BudgetModel::auth()->where('id', $id)->update([
            'deleted_at' => time()
        ]);
        return $this->render([
            'data' => true
        ]);
    }

    public function refreshAction() {
        BudgetRepository::refreshSpent();
        return $this->render([
            'data' => true
        ]);
    }

    public function statisticsAction($id) {
        $data = BudgetModel::find($id);
        $log_list = $data->getLogs();
        $sum = array_sum($log_list);
        $budget_sum = count($log_list) * $data->budget;
        return $this->render(compact('data', 'log_list', 'sum', 'budget_sum'));
    }
}