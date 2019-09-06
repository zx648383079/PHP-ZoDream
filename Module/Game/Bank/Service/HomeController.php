<?php
namespace Module\Game\Bank\Service;

use Module\Game\Bank\Domain\Model\BankProductModel;

class HomeController extends Controller {

    public function indexAction() {
        $model_list = BankProductModel::orderBy('id', 'desc')->page();
        return $this->show(compact('model_list'));
    }
}