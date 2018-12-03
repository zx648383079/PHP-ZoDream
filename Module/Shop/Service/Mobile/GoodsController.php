<?php
namespace Module\Shop\Service\Mobile;

use Module\Shop\Domain\Model\CommentModel;
use Module\Shop\Domain\Model\GoodsModel;

class GoodsController extends Controller {

    public function indexAction($id) {
        $goods = GoodsModel::find($id);
        $goods_list = GoodsModel::where('is_best', 1)->limit(3)->all();
        $comment_list = CommentModel::with('images', 'user')->where('item_type', 0)
            ->where('item_id', $id)->limit(3)->all();
        return $this->show(compact('goods', 'goods_list', 'comment_list'));
    }

    public function priceAction($id, $amount = 1) {
        $goods = GoodsModel::find($id);
        $price = $goods->getPrice($amount);
        return $this->jsonSuccess($price);
    }
}