<?php
namespace Module\Game\Bank\Service;

use Module\Game\Bank\Domain\Model\BankLogModel;
use Module\Game\Bank\Domain\Model\BankProductModel;

class HomeController extends Controller {

    public function indexAction() {
        $model_list = BankProductModel::orderBy('risk', SORT_ASC)->thenBy('earnings', SORT_ASC)->thenBy('id', SORT_DESC)->page();
        return $this->show(compact('model_list'));
    }

    public function investAction(int $id, float|int $money) {
        if (auth()->user()->money < $money) {
            return $this->renderFailure('您的账户金额不足');
        }
        if (BankLogModel::invest(auth()->id(), $id, $money)) {
            return $this->renderData('', '投资成功');
        }
        return $this->renderFailure('投资失败!');
    }
}