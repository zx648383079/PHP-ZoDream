<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api;

use Domain\Model\SearchModel;
use Module\Shop\Domain\Models\ArticleCategoryModel;
use Module\Shop\Domain\Models\ArticleModel;
use Module\Shop\Domain\Repositories\ArticleRepository;

class ArticleController extends Controller {

    public function indexAction(int $id = 0, int $category = 0, string $keywords = '') {
        if ($id > 0) {
            return $this->detailAction($id);
        }
        $model_list = ArticleModel::with('category')
            ->when($category > 0, function ($query) use ($category) {
                $query->where('cat_id', $category);
            })
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'title');
            })
            ->select(ArticleModel::THUMB_MODE)->page();
        return $this->renderPage($model_list);
    }

    public function detailAction(int $id) {
        $article = ArticleModel::find($id);
        return $this->render($article);
    }

    public function helpAction() {
        return $this->renderData(ArticleRepository::getHelps());
    }

    public function noticeAction() {
        return $this->renderData(ArticleRepository::getNotices());
    }

    public function categoryAction(int $parent_id = 0) {
        $cat_list = ArticleCategoryModel::where('parent_id', $parent_id)->get();
        return $this->render($cat_list);
    }

    public function suggestAction(string $keywords) {
        $data = ArticleModel::when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, 'title');
        })->take(4)->get('id', 'title');
        return $this->render(compact('data'));
    }
}