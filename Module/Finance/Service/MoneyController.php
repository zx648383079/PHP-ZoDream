<?php
namespace Module\Finance\Service;

use Module\Finance\Domain\Model\FinancialProductModel;
use Module\Finance\Domain\Model\FinancialProjectModel;
use Module\Finance\Domain\Model\LogModel;
use Module\Finance\Domain\Model\MoneyAccountModel;



class MoneyController extends Controller {

    public function indexAction() {
        $account_list = MoneyAccountModel::all();
        $total = 0;
        foreach ($account_list as $item) {
            $total += $item->total;
        }
        $product_list = FinancialProductModel::all();
        $project_list = FinancialProjectModel::select('name', 'money')->all();
        return $this->show(compact('account_list', 'total', 'product_list', 'project_list'));
    }

    public function accountAction() {
        $account_list = MoneyAccountModel::orderBy('id', 'desc')->all();
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
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->jsonSuccess([
                'url' => url('./money/account')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function changeAccountAction($id) {
        MoneyAccountModel::record()->where('id', $id)->updateBool('status');
        return $this->jsonSuccess([
            'url' => url('./money/account')
        ]);
    }

    public function deleteAccountAction($id) {
        MoneyAccountModel::where('id', $id)->update([
            'deleted_at' => time()
        ]);
        return $this->jsonSuccess([
            'url' => url('./money/account')
        ]);
    }

    public function projectAction() {
        $model_list = FinancialProjectModel::with('product')->orderBy('id', 'desc')->all();
        return $this->show(compact('model_list'));
    }

    public function addProjectAction() {
        return $this->editProjectAction(0);
    }

    public function editProjectAction($id) {
        $model = FinancialProjectModel::findOrNew($id);
        $product_list = FinancialProductModel::all();
        $account_list = MoneyAccountModel::all();
        return $this->show('create_project', compact('model', 'product_list', 'account_list'));
    }

    public function saveProjectAction() {
        $model = new FinancialProjectModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->jsonSuccess([
                'url' => url('./money/project')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteProjectAction($id) {
        FinancialProjectModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => url('./money/project')
        ]);
    }

    public function confirmEarningsAction($id) {
        $model = FinancialProjectModel::find($id);
        return $this->show('confirm_project', compact('model'));
    }

    public function saveEarningsAction() {
        $project = FinancialProjectModel::find(app('request')->get('id'));
        $model = new LogModel();
        $model->money = floatval(app('request')->get('money'));
        $model->account_id = $project->account_id;
        $model->project_id = $project->id;
        $model->type = LogModel::TYPE_INCOME;
        $model->happened_at = date('Y-m-d H:i:s');
        $model->remark = sprintf('理财项目 %s 收益', $project->name);
        if ($model->save()) {
            return $this->jsonSuccess([
                'url' => url('./money/project')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function productAction() {
        $model_list = FinancialProductModel::orderBy('id', 'desc')->all();
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
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->jsonSuccess([
                'url' => url('./money/product')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteProductAction($id) {
        FinancialProductModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => url('./money/product')
        ]);
    }

    public function changeProductAction($id) {
        FinancialProductModel::record()->where('id', $id)->updateBool('status');
        return $this->jsonSuccess([
            'url' => url('./money/product')
        ]);
    }
}