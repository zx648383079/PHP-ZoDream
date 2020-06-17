<?php
namespace Module\Legwork\Service\Api;

use Module\Legwork\Domain\Model\OrderModel;
use Module\Legwork\Domain\Repositories\OrderRepository;
use Zodream\Infrastructure\Http\Request;
use Zodream\Route\Controller\RestController;

class OrderController extends RestController {

    protected function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction($status = 0) {
        $data = OrderRepository::getList($status);
        return $this->renderPage($data);
    }

    public function createAction(Request $request) {
        try {
            $order = OrderRepository::create($request->get());
            $data = OrderRepository::pay($order);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render(compact('data'));
    }

    public function payAction($id) {
        $order = OrderModel::query()->where('user_id', auth()->id())->where('id', $id)
            ->first();
        if (empty($order)) {
            return $this->renderFailure('订单不存在');
        }
        if ($order->status != OrderModel::STATUS_UN_PAY) {
            return $this->renderFailure('此订单无法支付');
        }
        try {
            $data = OrderRepository::pay($order);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render(compact('data'));
    }

    public function commentAction($id, $rank = 10) {
        try {
            OrderRepository::comment($id, $rank);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render([
            'data' => true
        ]);
    }

    public function cancelAction($id) {
        try {
            OrderRepository::cancel($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render([
            'data' => true
        ]);
    }
}
