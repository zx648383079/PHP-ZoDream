<?php
namespace Module\Shop\Service;


use Module\Shop\Domain\Model\CommentModel;
use Module\Shop\Domain\Model\GoodsModel;

class HomeController extends Controller {

    public function indexAction() {
        $new_goods = GoodsModel::limit(7)->select('id', 'name', 'thumb', 'price')->all();
        $best_goods = $new_goods;
        $category_goods = array_slice($best_goods, 0, 4);
        $comment_goods = CommentModel::with('goods', 'user')->where('item_type', 0)->limit(6)->all();
        return $this->sendWithShare()->show(compact('new_goods', 'best_goods', 'category_goods', 'comment_goods'));
    }
}