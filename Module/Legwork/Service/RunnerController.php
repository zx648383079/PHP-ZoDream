<?php
declare(strict_types=1);
namespace Module\Legwork\Service;

use Module\Auth\Domain\Concerns\CheckRole;
use Module\Legwork\Domain\Model\OrderModel;
use Module\Legwork\Domain\Repositories\WaiterRepository;

class RunnerController extends Controller {

    use CheckRole;

    public function rules() {
        return [
            '*' => 'shop_admin'
        ];
    }

    public function indexAction() {
        $order_list = WaiterRepository::waitTakingList();
        return $this->show(compact('order_list'));
    }

    public function orderAction(int $status = 0) {
        $order_list = WaiterRepository::orderList('', $status);
        $status_list = OrderModel::$status_list;
        return $this->show(compact('order_list', 'status', 'status_list'));
    }

    public function takingAction(int $id) {
        try {
            WaiterRepository::taking($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'refresh' => true
        ], '已成功接单');
    }

    public function takenAction(int $id) {
        try {
            WaiterRepository::taken($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'refresh' => true
        ], '本次服务已完成');
    }
}