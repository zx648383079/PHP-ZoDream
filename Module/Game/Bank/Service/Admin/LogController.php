<?php
namespace Module\Game\Bank\Service\Admin;

use Module\Game\Bank\Domain\Model\BankLogModel;

class LogController extends Controller {

    public function indexAction() {
        $model_list = BankLogModel::with('user', 'product')->orderBy('id', 'desc')->page();
        return $this->show(compact('model_list'));
    }
}