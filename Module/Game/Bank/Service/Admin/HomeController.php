<?php
namespace Module\Game\Bank\Service\Admin;

use Module\Game\Bank\Domain\Model\BankLogModel;

class HomeController extends Controller {

    public function indexAction() {
        $total = (int)BankLogModel::where('status', '<', 1)->sum('money');
        $income = (int)BankLogModel::where('status', '>', 0)->sum('real_money - money');
        return $this->show(compact('total', 'income'));
    }
}