<?php
namespace Module\Finance\Service;

use Module\Finance\Domain\Model\LogModel;
use Module\Finance\Domain\Model\MoneyAccountModel;
use Module\ModuleController;

class HomeController extends ModuleController {

    public function indexAction() {
        $time = strtotime(date('Y-m'));
        $now_income = LogModel::month($time)->where('type', LogModel::TYPE_INCOME)->sum('money');
        $now_expenditure = LogModel::month($time)->where('type', LogModel::TYPE_EXPENDITURE)->sum('money');
        $now_income_count = LogModel::month($time)->where('type', LogModel::TYPE_INCOME)->count();
        $now_expenditure_count = LogModel::month($time)->where('type', LogModel::TYPE_EXPENDITURE)->count();


        $time = strtotime(date('-1 month'));
        $y_income = LogModel::month($time)->where('type', LogModel::TYPE_INCOME)->sum('money');
        $y_expenditure = LogModel::month($time)->where('type', LogModel::TYPE_EXPENDITURE)->sum('money');
        $y_income_count = LogModel::month($time)->where('type', LogModel::TYPE_INCOME)->count();
        $y_expenditure_count = LogModel::month($time)->where('type', LogModel::TYPE_EXPENDITURE)->count();
        $account_list = MoneyAccountModel::all();

        return $this->show(compact('now_income', 'now_income_count', 'now_expenditure',
            'now_expenditure_count', 'y_income', 'y_income_count',
            'y_expenditure', 'y_expenditure_count', 'account_list'));
    }
}