<?php
namespace Module\Finance\Service;

use Module\Finance\Domain\Model\FinancialProjectModel;
use Module\Finance\Domain\Model\LogModel;
use Module\Finance\Domain\Model\MoneyAccountModel;

class HomeController extends Controller {


    public function indexAction() {
        $time = strtotime(date('Y-m'));
        $now_income = LogModel::auth()->month($time)->where('type', LogModel::TYPE_INCOME)->sum('money');
        $now_expenditure = LogModel::auth()->month($time)->where('type', LogModel::TYPE_EXPENDITURE)->sum('money');
        $now_income_count = LogModel::auth()->month($time)->where('type', LogModel::TYPE_INCOME)->count();
        $now_expenditure_count = LogModel::auth()->month($time)->where('type', LogModel::TYPE_EXPENDITURE)->count();


        $time = strtotime('-1 month');
        $y_income = LogModel::auth()->month($time)->where('type', LogModel::TYPE_INCOME)->sum('money');
        $y_expenditure = LogModel::auth()->month($time)->where('type', LogModel::TYPE_EXPENDITURE)->sum('money');
        $y_income_count = LogModel::auth()->month($time)->where('type', LogModel::TYPE_INCOME)->count();
        $y_expenditure_count = LogModel::auth()->month($time)->where('type', LogModel::TYPE_EXPENDITURE)->count();
        $account_list = MoneyAccountModel::auth()->all();

        $project_list = FinancialProjectModel::auth()->all();

        return $this->show(compact('now_income', 'now_income_count', 'now_expenditure',
            'now_expenditure_count', 'y_income', 'y_income_count',
            'y_expenditure', 'y_expenditure_count', 'account_list', 'project_list'));
    }
}