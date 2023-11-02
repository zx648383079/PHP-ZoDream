<?php
declare(strict_types=1);
namespace Module\Finance\Service;

use Module\Finance\Domain\Model\BudgetModel;
use Module\Finance\Domain\Repositories\BudgetRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class BudgetController extends Controller {

    public function indexAction() {
        $items = BudgetRepository::getList();
        return $this->show(compact('items'));
    }

    public function addAction() {
        return $this->editAction(0);
    }

    public function editAction(int $id) {
        try {
            $model = $id > 0 ? BudgetRepository::get($id) : new BudgetModel();
        } catch (\Exception $ex) {
            return $this->redirectWithMessage('./budget', $ex->getMessage());
        }
        return $this->show('create', compact('model'));
    }

    public function saveAction(Request $request) {
        try {
            $model = BudgetRepository::save($request->get());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => url('./budget')
        ]);
    }

    public function deleteAction(int $id) {
        BudgetRepository::softDelete($id);
        return $this->renderData([
            'url' => url('./budget')
        ]);
    }

    public function refreshAction() {
        BudgetRepository::refreshSpent();
        return $this->renderData([
            'url' => url('./budget')
        ]);
    }

    public function statisticsAction(int $id) {
        try {
            $data = BudgetRepository::statistics($id);
        } catch (\Exception $ex) {
            return $this->redirectWithMessage('./budget', $ex->getMessage());
        }
        $data['model'] = $data['data'];
        return $this->show($data);
    }
}