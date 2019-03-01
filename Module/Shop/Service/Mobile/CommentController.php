<?php
namespace Module\Shop\Service\Mobile;


use Module\Shop\Domain\Model\CommentImageModel;
use Module\Shop\Domain\Model\CommentModel;
use Module\Shop\Domain\Model\OrderGoodsModel;
use Module\Shop\Domain\Model\OrderLogModel;
use Module\Shop\Domain\Model\OrderModel;

class CommentController extends Controller {

    protected function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction($status = 0) {
        $goods_list = OrderGoodsModel::where('user_id', auth()->id())
            ->when($status > 0, function ($query) {
                $query->where('comment_id', '>', 0);
            }, function ($query) {
                $query->where('status', OrderModel::STATUS_RECEIVED)
                    ->where('comment_id', 0);
            })->page();
        return $this->show(compact('goods_list', 'status'));
    }

    public function createAction($goods = 0, $order = 0) {
        $goods_list = OrderGoodsModel::where('user_id', auth()->id())
            ->where('comment_id', 0)
            ->where('status', OrderModel::STATUS_RECEIVED)
            ->when($goods > 0, function ($query) use ($goods) {
                $query->where('id', intval($goods));
            }, function ($query) use ($order) {
                $query->where('order_id', intval($order));
            })->get();
        if (empty($goods_list)) {
            return $this->redirectWithMessage($this->getUrl('comment'), '请选择商品');
        }
        return $this->show(compact('goods_list'));
    }

    public function saveAction($goods, $content, $images = [], $rank = 5) {
        $goods = OrderGoodsModel::where('user_id', auth()->id())
            ->where('comment_id', 0)
            ->where('status', OrderModel::STATUS_RECEIVED)->where('id', intval($goods))
            ->first();
        if (empty($goods)) {
            return $this->jsonFailure('可评价的商品不存在');
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
            return $this->jsonFailure('评价失败');
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
        return $this->jsonSuccess([
            'url' => $this->getUrl('comment')
        ]);
    }

}