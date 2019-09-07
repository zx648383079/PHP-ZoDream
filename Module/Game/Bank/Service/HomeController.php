<?php
namespace Module\Game\Bank\Service;

use Module\Game\Bank\Domain\Model\BankLogModel;
use Module\Game\Bank\Domain\Model\BankProductModel;

class HomeController extends Controller {

    public function indexAction() {
        $model_list = BankProductModel::orderBy('risk', 'asc')->orderBy('earnings', 'asc')->orderBy('id', 'desc')->page();
        return $this->show(compact('model_list'));
    }

    public function investAction($id, $money) {
        if (auth()->user()->money < $money) {
            return $this->jsonFailure('您的账户金额不足');
        }
        if (BankLogModel::invest(auth()->id(), $id, $money)) {
            return $this->jsonSuccess('', '投资成功');
        }
        return $this->jsonFailure('投资失败!');
    }
}