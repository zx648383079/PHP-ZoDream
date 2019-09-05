<?php
namespace Module\Game\CheckIn\Service\Admin;

use Module\Game\CheckIn\Domain\Model\CheckInModel;

class HomeController extends Controller {

    public function indexAction() {
        $today_count = CheckInModel::today()->count();
        $yesterday_count = CheckInModel::yesterday()->count();
        $max_day = CheckInModel::today()->max('running');
        $avg_day = round(CheckInModel::today()->avg('running'), 2);
        $day_list = CheckInModel::today()->groupBy('running')->asArray()->get('COUNT(*) AS count,running as day');
        return $this->show(compact('today_count', 'yesterday_count', 'max_day', 'avg_day', 'day_list'));
    }
}