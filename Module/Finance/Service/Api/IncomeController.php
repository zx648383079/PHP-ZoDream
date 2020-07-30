<?php
declare(strict_types=1);
namespace Module\Finance\Service\Api;

use Module\Finance\Domain\Model\LogModel;

class IncomeController extends Controller {

    public function indexAction(string $month = '') {
        if (empty($month)) {
            $month = date('Y-m');
        }
        $time = strtotime($month);
        $income_list = LogModel::auth()->month($time)->where('type', LogModel::TYPE_INCOME)->orderBy('happened_at', 'desc')->all();
        $expenditure_list = LogModel::auth()->month($time)->where('type', LogModel::TYPE_EXPENDITURE)->orderBy('happened_at', 'desc')->all();
        $log_list = LogModel::auth()->month($time)->orderBy('happened_at', 'desc')->all();
        $day_length = date('t', $time);
        $income_days = LogModel::getMonthLogs($income_list, $day_length);
        $expenditure_days = LogModel::getMonthLogs($expenditure_list, $day_length);
        return $this->render(compact('month', 'income_days', 'income_list', 'expenditure_list', 'expenditure_days', 'log_list', 'day_length'));
    }
}