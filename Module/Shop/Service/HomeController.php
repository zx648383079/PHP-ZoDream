<?php
declare(strict_types=1);
namespace Module\Shop\Service;

use Module\Shop\Domain\Repositories\BrandRepository;
use Module\Shop\Domain\Repositories\CategoryRepository;
use Module\Shop\Domain\Repositories\CommentRepository;
use Module\Shop\Domain\Repositories\GoodsRepository;

class HomeController extends Controller {

    public function indexAction() {
        if (request()->isMobile()) {
            return $this->redirect('./mobile');
        }
        return $this->sendWithShare()->show();
    }

    public function brandAction() {
        $brand_list = BrandRepository::recommend();
        return $this->renderData(compact('brand_list'));
    }

    public function newAction() {
        $new_goods = GoodsRepository::getRecommendQuery('is_new')->all();
        return $this->renderData(compact('new_goods'));
    }

    public function bestAction() {
        $best_goods = GoodsRepository::getRecommendQuery('is_best')->limit(7)->all();
        return $this->renderData(compact('best_goods'));
    }

    public function categoryAction() {
        $this->layout = false;
        $floor_categories = CategoryRepository::getHomeFloor();
        return $this->renderData($this->renderHtml(compact('floor_categories')));
    }

    public function commentAction() {
        $comment_goods = CommentRepository::recommend();
        return $this->renderData(compact('comment_goods'));
    }
}