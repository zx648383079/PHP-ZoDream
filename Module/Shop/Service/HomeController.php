<?php
namespace Module\Shop\Service;


use Module\Shop\Domain\Models\Advertisement\AdModel;
use Module\Shop\Domain\Models\CommentModel;
use Module\Shop\Domain\Repositories\CategoryRepository;
use Module\Shop\Domain\Repositories\GoodsRepository;

class HomeController extends Controller {

    public function indexAction() {
        if (app('request')->isMobile()) {
            return $this->redirect('./mobile');
        }
        $banners = AdModel::banners(false);
        $new_goods = GoodsRepository::getRecommendQuery('is_new')->all();
        $best_goods = GoodsRepository::getRecommendQuery('is_best')->limit(7)->all();
        $floor_categories = CategoryRepository::getHomeFloor();
        $comment_goods = CommentModel::with('goods', 'user')->where('item_type', 0)->limit(6)->all();
        return $this->sendWithShare()->show(compact('new_goods', 'best_goods',
            'floor_categories', 'comment_goods', 'banners'));
    }
}