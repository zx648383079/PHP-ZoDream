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
        return $this->sendWithShare()->show(compact('banners'));
    }

    public function brandAction() {
        return $this->jsonSuccess([]);
    }

    public function newAction() {
        $new_goods = GoodsRepository::getRecommendQuery('is_new')->all();
        return $this->jsonSuccess(compact('new_goods'));
    }

    public function bestAction() {
        $best_goods = GoodsRepository::getRecommendQuery('is_best')->limit(7)->all();
        return $this->jsonSuccess(compact('best_goods'));
    }

    public function categoryAction() {
        $this->layout = false;
        $floor_categories = CategoryRepository::getHomeFloor();
        return $this->jsonSuccess($this->renderHtml(compact('floor_categories')));
    }

    public function commentAction() {
        $comment_goods = CommentModel::with('goods', 'user')->where('item_type', 0)->limit(6)->all();
        return $this->jsonSuccess(compact('comment_goods'));
    }
}