<?php
declare(strict_types=1);
namespace Module\Legwork\Service\Api;

use Module\Legwork\Domain\Model\OrderModel;
use Module\Legwork\Domain\Repositories\OrderRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class OrderController extends Controller {

    public function indexAction(int $status = 0) {
        return $this->renderPage(OrderRepository::getList($status));
    }

    public function createAction(Request $request) {
        try {
            $order = OrderRepository::create($request->get());
            $data = OrderRepository::pay($order);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData($data);
    }

    public function payAction($id) {
        /** @var OrderModel $order */
        $order = OrderModel::query()
            ->where('user_id', auth()->id())
            ->where('id', $id)
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
        return $this->renderData(true);
    }

    public function cancelAction($id) {
        try {
            OrderRepository::cancel($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}
