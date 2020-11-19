<?php
declare(strict_types=1);
namespace Module\Finance\Service;

use Module\Finance\Domain\Model\FinancialProductModel;
use Module\Finance\Domain\Model\FinancialProjectModel;
use Module\Finance\Domain\Model\MoneyAccountModel;
use Module\Finance\Domain\Repositories\AccountRepository;
use Module\Finance\Domain\Repositories\ProductRepository;
use Module\Finance\Domain\Repositories\ProjectRepository;
use Zodream\Infrastructure\Http\Request;

class MoneyController extends Controller {

    public function indexAction() {
        $account_list = MoneyAccountModel::auth()->all();
        $total = 0;
        foreach ($account_list as $item) {
            $total += $item->total;
        }
        $product_list = FinancialProductModel::auth()->all();
        $project_list = FinancialProjectModel::auth()->select('name', 'money')->all();
        return $this->show(compact('account_list', 'total', 'product_list', 'project_list'));
    }

    public function accountAction() {
        $account_list = MoneyAccountModel::auth()->orderBy('id', 'desc')->all();
        return $this->show(compact('account_list'));
    }

    public function addAccountAction() {
        return $this->editAccountAction(0);
    }

    public function editAccountAction(int $id) {
        try {
            $model = $id > 0 ? AccountRepository::get($id) : new MoneyAccountModel();
        } catch (\Exception $ex) {
            return $this->redirectWithMessage('./money/account', $ex->getMessage());
        }
        return $this->show('create_account', compact('model'));
    }

    public function saveAccountAction(Request $request) {
        try {
            $model = AccountRepository::save($request->get());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => url('./money/account')
        ]);
    }

    public function changeAccountAction(int $id) {
        AccountRepository::change($id);
        return $this->renderData([
            'url' => url('./money/account')
        ]);
    }

    public function deleteAccountAction(int $id) {
        AccountRepository::softDelete($id);
        return $this->renderData([
            'url' => url('./money/account')
        ]);
    }

    public function projectAction() {
        $model_list = FinancialProjectModel::auth()->with('product')->orderBy('id', 'desc')->all();
        return $this->show(compact('model_list'));
    }

    public function addProjectAction() {
        return $this->runMethodNotProcess('editProject', ['id' => 0]);
    }

    public function editProjectAction($id) {
        try {
            $model = $id > 0 ? ProjectRepository::get($id) : new FinancialProjectModel();
        } catch (\Exception $ex) {
            return $this->redirectWithMessage('./money/project', $ex->getMessage());
        }
        $product_list = FinancialProductModel::auth()->all();
        $account_list = MoneyAccountModel::auth()->all();
        return $this->show('create_project', compact('model', 'product_list', 'account_list'));
    }

    public function saveProjectAction(Request $request) {
        try {
            $model = ProjectRepository::save($request->get());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => url('./money/project')
        ]);
    }

    public function deleteProjectAction(int $id) {
        ProjectRepository::remove($id);
        return $this->renderData([
            'url' => url('./money/project')
        ]);
    }

    public function confirmEarningsAction(int $id) {
        try {
            $model = ProjectRepository::get($id);
        } catch (\Exception $ex) {
            return $this->redirectWithMessage('./money/project', $ex->getMessage());
        }
        return $this->show('confirm_project', compact('model'));
    }

    public function saveEarningsAction(int $id, float $money) {
        try {
            $model = ProjectRepository::earnings($id, $money);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => url('./money/project')
        ]);
    }

    public function productAction() {
        $model_list = FinancialProductModel::auth()->orderBy('id', 'desc')->all();
        return $this->show(compact('model_list'));
    }

    public function addProductAction() {
        return $this->editProductAction(0);
    }

    public function editProductAction(int $id) {
        try {
            $model = $id > 0 ? ProductRepository::get($id) : new FinancialProductModel();
        } catch (\Exception $ex) {
            return $this->redirectWithMessage('./money/product', $ex->getMessage());
        }
        return $this->show('create_product', compact('model'));
    }

    public function saveProductAction(Request $request) {
        try {
            $model = ProductRepository::save($request->get());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => url('./money/product')
        ]);
    }

    public function deleteProductAction(int $id) {
        ProductRepository::remove($id);
        return $this->renderData([
            'url' => url('./money/product')
        ]);
    }

    public function changeProductAction(int $id) {
        ProductRepository::change($id);
        return $this->renderData([
            'url' => url('./money/product')
        ]);
    }
}