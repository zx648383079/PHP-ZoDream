<?php
namespace Module\Finance\Service;

use Module\Finance\Domain\Model\MoneyAccountModel;
use Module\Finance\Domain\Model\BankModel;
use Module\ModuleController;
use Zodream\Service\Routing\Url;

class MoneyController extends ModuleController {

    public function indexAction() {
        $account_list = MoneyAccountModel::all();
        return $this->show(compact('account_list'));
    }

    public function accountAction() {
        $account_list = MoneyAccountModel::with('bank')->all();
        return $this->show(compact('account_list'));
    }

    public function addAccountAction() {
        return $this->editAccountAction(0);
    }

    public function editAccountAction($id) {
        $model = MoneyAccountModel::findOrNew($id);
        $form_list = BankModel::all();
        return $this->show('create_account', compact('model', 'form_list'));
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

    public function bankAction() {
        $bank_list = BankModel::all();
        return $this->show(compact('bank_list'));
    }

    public function addBankAction() {
        return $this->editBankAction(0);
    }

    public function editBankAction($id) {
        $model = BankModel::findOrNew($id);
        return $this->show('create_bank', compact('model'));
    }

    public function saveBankAction() {
        $model = new BankModel();
        if ($model->load() && $model->save()) {
            return $this->jsonSuccess([
                'url' => (string)Url::to('./money/bank')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

}