<?php
namespace Module\Shop\Service\Api;

use Module\Shop\Domain\Models\CommentImageModel;
use Module\Shop\Domain\Models\CommentModel;
use Module\Shop\Domain\Models\OrderAddressModel;
use Module\Shop\Domain\Models\OrderGoodsModel;
use Module\Shop\Domain\Models\OrderLogModel;
use Module\Shop\Domain\Models\OrderModel;
use Module\Shop\Domain\Models\Scene\Order;
use Module\Shop\Domain\Repositories\OrderRepository;

class OrderController extends Controller {

    protected function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction($id = 0,
                                $status = 0) {
        if ($id > 0) {
            return $this->infoAction($id);
        }
        $order_list = OrderRepository::getList($status);
        return $this->renderPage($order_list);
    }

    public function infoAction($id) {
        $order = Order::findWithAuth($id);
        $address = OrderAddressModel::where('order_id', $id)->one();
        $data = $order->toArray();
        $data['address'] = $address;
        return $this->render($data);
    }

    public function countAction() {
        return $this->render(OrderRepository::getSubtotal());
    }

    public function receiveAction($id) {
        try {
            $order = OrderRepository::receive($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        $address = OrderAddressModel::where('order_id', $id)->one();
        $data = $order->toArray();
        $data['address'] = $address;
        return $this->render($data);
    }

    public function cancelAction($id) {
        try {
            $order = OrderRepository::cancel($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        $address = OrderAddressModel::where('order_id', $id)->one();
        $data = $order->toArray();
        $data['address'] = $address;
        return $this->render($data);
    }

    public function repurchaseAction($id) {
        try {
            OrderRepository::repurchase($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render([
            'data' => true
        ]);
    }

    public function logisticsAction($id) {
        $data = [];
        return $this->render(compact('data'));
    }

    public function commentAction($status) {
        $goods_list = OrderGoodsModel::where('user_id', auth()->id())
            ->when($status > 0, function ($query) {
                $query->where('comment_id', '>', 0);
            }, function ($query) {
                $query->where('status', OrderModel::STATUS_RECEIVED)
                    ->where('comment_id', 0);
            })->page();
        return $this->renderPage($goods_list);
    }

    public function commentGoodsAction($goods = 0, $order = 0) {
        $goods_list = OrderGoodsModel::where('user_id', auth()->id())
            ->where('comment_id', 0)
            ->where('status', OrderModel::STATUS_RECEIVED)
            ->when($goods > 0, function ($query) use ($goods) {
                $query->where('id', intval($goods));
            }, function ($query) use ($order) {
                $query->where('order_id', intval($order));
            })->get();
        return $this->render($goods_list);
    }

    public function commentSaveAction($goods, $content, $images = [], $rank = 5) {
        $goods = OrderGoodsModel::where('user_id', auth()->id())
            ->where('comment_id', 0)
            ->where('status', OrderModel::STATUS_RECEIVED)->where('id', intval($goods))
            ->first();
        if (empty($goods)) {
            return $this->renderFailure('可评价的商品不存在');
        }
        $model = CommentModel::create([
            'user_id' => $goods->user_id,
            'item_type' => 0,
            'item_id' => $goods->goods_id,
            'title' => $goods->name,
            'content' => $content,
            'rank' => $rank
        ]);
        if (empty($model)) {
            return $this->renderFailure('评价失败');
        }
        if (!empty($images)) {
            CommentImageModel::query()->insert(array_map(function ($item) use ($model) {
                return [
                    'comment_id' => $model->id,
                    'image' => $item
                ];
            }, $images));
        }
        $goods->status = OrderModel::STATUS_FINISH;
        $goods->comment_id = $model->id;
        $goods->save();
        $count = OrderGoodsModel::where('order_id', $goods->order_id)
            ->where('comment_id', 0)
            ->where('status', OrderModel::STATUS_RECEIVED)->count();
        if ($count < 1) {
            OrderLogModel::finish(OrderModel::find($goods->order_id));
        }
        return $this->render(['data' => true]);
    }
}