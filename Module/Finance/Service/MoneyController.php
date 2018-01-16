<?php
namespace Module\Finance\Service;

use Module\Finance\Domain\Model\FinancialProductModel;
use Module\Finance\Domain\Model\FinancialProjectModel;
use Module\Finance\Domain\Model\MoneyAccountModel;
use Module\ModuleController;
use Zodream\Service\Routing\Url;

class MoneyController extends ModuleController {

    public function indexAction() {
        $account_list = MoneyAccountModel::all();
        return $this->show(compact('account_list'));
    }

    public function accountAction() {
        $account_list = MoneyAccountModel::all();
        return $this->show(compact('account_list'));
    }

    public function addAccountAction() {
        return $this->editAccountAction(0);
    }

    public function editAccountAction($id) {
        $model = MoneyAccountModel::findOrNew($id);
        return $this->show('create_account', compact('model'));
    }

    public function saveAccountAction() {
        $model = new MoneyAccountModel();
        if ($model->load() && $model->save()) {
            return $this->jsonSuccess([
                'url' => (string)Url::to('./money/account')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function projectAction() {
        $model_list = FinancialProjectModel::with('product')->all();
        return $this->show(compact('model_list'));
    }

    public function addProjectAction() {
        return $this->editProjectAction(0);
    }

    public function editProjectAction($id) {
        $model = FinancialProjectModel::findOrNew($id);
        $product_list = FinancialProductModel::all();
        return $this->show('create_project', compact('model', 'product_list'));
    }

    public function saveProjectAction() {
        $model = new FinancialProjectModel();
        if ($model->load() && $model->save()) {
            return $this->jsonSuccess([
                'url' => (string)Url::to('./money/project')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }


    public function productAction() {
        $model_list = FinancialProductModel::all();
        return $this->show(compact('model_list'));
    }

    public function addProductAction() {
        return $this->editProductAction(0);
    }

    public function editProductAction($id) {
        $model = FinancialProductModel::findOrNew($id);
        return $this->show('create_product', compact('model'));
    }

    public function saveProductAction() {
        $model = new FinancialProductModel();
        if ($model->load() && $model->save()) {
            return $this->jsonSuccess([
                'url' => (string)Url::to('./money/product')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

}