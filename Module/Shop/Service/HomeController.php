<?php
namespace Module\Shop\Service;


use Module\Shop\Domain\Models\CommentModel;
use Module\Shop\Domain\Models\GoodsModel;
use Module\Shop\Domain\Models\GoodsSimpleModel;

class HomeController extends Controller {

    public function indexAction() {
        if (app('request')->isMobile()) {
            return $this->redirect('./mobile');
        }
        $new_goods = GoodsSimpleModel::limit(7)->all();
        $best_goods = $new_goods;
        $category_goods = array_slice($best_goods, 0, 4);
        $comment_goods = CommentModel::with('goods', 'user')->where('item_type', 0)->limit(6)->all();
        return $this->sendWithShare()->show(compact('new_goods', 'best_goods', 'category_goods', 'comment_goods'));
    }
}