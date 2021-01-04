<?php
namespace Module\Legwork\Service;

use Module\Legwork\Domain\Model\OrderModel;
use Module\Legwork\Domain\Model\ServiceModel;
use Module\Legwork\Domain\Repositories\OrderRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class OrderController extends Controller {

    public function indexAction($status = 0, $id = 0) {
        $order_list = OrderRepository::getList($status, $id);
        $status_list = OrderModel::$status_list;
        return $this->show(compact('order_list', 'status_list', 'status'));
    }

    public function createAction(int $id) {
        $service = ServiceModel::find($id);
        return $this->show(compact('service'));
    }

    public function saveAction(Request $request) {
        try {
            $order = OrderRepository::create($request->get());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => url('./order', ['id' => $order->id])
        ]);
    }

    public function payAction($id) {
        return $this->redirectWithMessage('./', '网页版暂不支持支付');
    }

    public function commentAction($id, $rank = 10) {
        try {
            OrderRepository::comment($id, $rank);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'refresh' => true
        ]);
    }

    public function cancelAction($id) {
        try {
            OrderRepository::cancel($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'refresh' => true
        ]);
    }
}