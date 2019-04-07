<?php
namespace Module\Shop\Service\Api;

use Module\Shop\Domain\Models\OrderModel;
use Module\Shop\Domain\Models\PayLogModel;

class PayController extends Controller {

    protected function methods() {
        return ['index' => ['POST']];
    }

    public function indexAction($order, $payment) {
        $order = OrderModel::find($order);
        if ($order->status != OrderModel::STATUS_UN_PAY) {
            return $this->renderFailure('此订单不能支付');
        }
        $log = PayLogModel::create([
            'type' => PayLogModel::TYPE_ORDER,
            'payment_id' => $payment,
            'user_id' => auth()->id(),
            'data' => $order->id,
            'status' => PayLogModel::STATUS_NONE,
            'amount' => $order->order_amount,
        ]);
        $data = [
            'success' => 'true', // 直接支付成功，比如余额支付
            'url' => '', // 返回链接需要跳转
            'html' => '', // 返回html 需要输入会自动提交表单post
            'params' => [] // 返回签名的参数，直接提交即可
        ];
        return $this->render(compact('data'));
    }
}