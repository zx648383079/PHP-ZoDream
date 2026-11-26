<?php
declare(strict_types=1);
namespace Module\Shop\Service\Mobile;

use Module\Shop\Domain\Models\ArticleCategoryModel;
use Module\Shop\Domain\Models\ArticleModel;

class ArticleController extends Controller {

    public function indexAction(int $id = 0) {
        if ($id > 0) {
            return $this->detailAction($id);
        }
        $model_list = ArticleCategoryModel::where('parent_id', 0)->page();
        return $this->show(compact('model_list'));
    }

    public function detailAction(int $id) {
        $article = ArticleModel::find($id);
        $cat = $article->category;
        $cat_list = ArticleCategoryModel::where('parent_id', $cat->parent_id)->get();
        return $this->show('detail', compact('article', 'cat', 'cat_list'));
    }

    public function categoryAction(int $id) {
        $cat = ArticleCategoryModel::find($id);
        $cat_list = ArticleCategoryModel::where('parent_id', $cat->parent_id)->get();
        $article_list = ArticleModel::where('cat_id', $id)->page();
        return $this->show(compact('cat', 'cat_list', 'article_list'));
    }
}