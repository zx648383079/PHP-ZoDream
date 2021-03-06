<?php
namespace Module\Legwork\Domain\Repositories;

use Domain\Model\SearchModel;
use Exception;
use Module\Legwork\Domain\Model\OrderLogModel;
use Module\Legwork\Domain\Model\OrderModel;
use Module\Legwork\Domain\Model\ServiceModel;
use Module\Shop\Domain\Models\PayLogModel;
use Module\Shop\Domain\Models\PaymentModel;
use Module\Shop\Domain\Repositories\PaymentRepository;

class OrderRepository {


    public static function getList(string $keywords = '', int $status = 0, int $id = 0, int $user_id = 0,
                                   int $service_id = 0,
                                   int $provider_id = 0, int $waiter_id = 0) {
        return OrderModel::query()
            ->with('service', 'user', 'provider', 'waiter')
            ->when($status > 0, function ($query) use ($status) {
                $query->where('status', $status);
            }, function ($query) {
                $query->where('status', '>=', OrderModel::STATUS_UN_PAY);
            })
            ->when(!empty($keywords), function ($query) use ($keywords) {
                $serviceId = SearchModel::searchWhere(ServiceModel::query(), ['name'])
                    ->where('status', ServiceModel::STATUS_ALLOW)
                    ->pluck('id');
                if (empty($serviceId)) {
                    return $query->isEmpty();
                }
                $query->whereIn('service_id', $serviceId);
            })
            ->when($id > 0, function ($query) use ($id) {
                $query->where('id', $id);
            })
            ->when($user_id > 0, function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })
            ->when($service_id > 0, function ($query) use ($service_id) {
                $query->where('service_id', $service_id);
            })
            ->when($provider_id > 0, function ($query) use ($provider_id) {
                $query->where('provider_id', $provider_id);
            })
            ->when($waiter_id > 0, function ($query) use ($waiter_id) {
                $query->where('waiter_id', $waiter_id);
            })
            ->orderBy('id', 'desc')->page();
    }

    public static function getSelfList(string $keywords = '', int $status = 0, int $id = 0) {
        return OrderModel::query()->with('service', 'provider', 'waiter')
            ->where('user_id', auth()->id())
            ->when($status > 0, function ($query) use ($status) {
                $query->where('status', $status);
            }, function ($query) {
                $query->where('status', '>=', OrderModel::STATUS_UN_PAY);
            })
            ->when(!empty($keywords), function ($query) use ($keywords) {
                $serviceId = ServiceModel::searchWhere(ServiceModel::query(), ['name'])
                    ->where('status', ServiceModel::STATUS_ALLOW)
                    ->pluck('id');
                if (empty($serviceId)) {
                    return $query->isEmpty();
                }
                $query->whereIn('service_id', $serviceId);
            })
            ->when($id > 0, function ($query) use ($id) {
                $query->where('id', $id);
            })
            ->orderBy('id', 'desc')->page();
    }

    public static function pay(OrderModel $order) {
        $payment = PaymentModel::where('code', 'wechat')->first();
        $log = PayLogModel::create([
            'type' => 27,
            'payment_id' => $payment->id,
            'user_id' => auth()->id(),
            'data' => $order->id,
            'status' => PayLogModel::STATUS_NONE,
            'amount' => $order->order_amount,
            'currency' => 'CNY',
            'payment_name' => $payment->name,
            'currency_money' => $order->order_amount,
            'begin_at' => time(),
        ]);
        return PaymentRepository::pay($log, $payment, [
            'order_id' => $order->id,
            'body' => '代取件订单支付',
        ]);
    }

    public static function comment($id, $rank = 10) {
        /** @var OrderModel $order */
        $order = OrderModel::query()
            ->where('user_id', auth()->id())
            ->where('id', $id)
            ->first();
        if (empty($order)) {
            throw new Exception('订单不存在');
        }
        if ($order->status != OrderModel::STATUS_TAKEN
            && $order->status != OrderModel::STATUS_TAKING_UN_DO
            && $order->status != OrderModel::STATUS_PAID_UN_TAKING) {
            throw new Exception('此订单无法评价');
        }
        $order->service_score = intval($rank);
        OrderLogModel::finish($order);
        return $order;
    }

    public static function cancel($id) {
        /** @var OrderModel $order */
        $order = OrderModel::query()->where('user_id', auth()->id())->where('id', $id)
            ->first();
        if (empty($order)) {
            throw new Exception('订单不存在');
        }
        if ($order->status != OrderModel::STATUS_UN_PAY) {
            throw new Exception('此订单无法取消，可以联系店主');
        }
        OrderLogModel::cancel($order);
        return $order;
    }

    public static function create(array $data) {
        if (!isset($data['service_id']) || $data['service_id'] < 1) {
            throw new Exception('选择服务');
        }
        $service = ServiceModel::find($data['service_id']);
        if (empty($service)) {
            throw new Exception('选择服务');
        }
        $form = $service->form;
        $remark = [];
        foreach ($form as $item) {
            $value = isset($data['remark'][$item['name']]) ? $data['remark'][$item['name']] : '';
            if (isset($item['required']) && $item['required'] && empty($value)) {
                throw new Exception(sprintf('%s 必填', $item['label']));
            }
            $remark[] = [
                'name' => $item['name'],
                'label' => $item['label'],
                'only' => isset($item['only']) ? $item['only'] : 0,
                'value' => $value
            ];
        }
        $order = new OrderModel();
        $order->user_id = auth()->id();
        $order->status = OrderModel::STATUS_UN_PAY;
        $order->service_id = $service->id;
        $order->remark = $remark;
        $order->amount = isset($data['amount']) && $data['amount'] > 0 ? intval($data['amount']) : 1;
        $order->order_amount = $service->price * $order->amount;
        if ($order->save()) {
            return $order;
        }
        throw new Exception($order->getFirstError());
    }
}