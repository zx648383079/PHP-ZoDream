<?php
declare(strict_types=1);
namespace Module\Shop\Service;

use Module\Shop\Domain\Models\ArticleCategoryModel;
use Module\Shop\Domain\Models\ArticleModel;

class ArticleController extends Controller {

    public function indexAction(int $id = 0) {
        if ($id > 0) {
            return $this->detailAction($id);
        }
        return $this->show();
    }

    public function detailAction(int $id) {
        $article = ArticleModel::find($id);
        $category = $article->category;
        $cat_list = ArticleCategoryModel::where('parent_id', $category->parent_id)->get();
        return $this->sendWithShare()->show('detail', compact('article', 'category', 'cat_list'));
    }

    public function categoryAction(int $id) {
        $cat = ArticleCategoryModel::find($id);
        $cat_list = ArticleCategoryModel::where('parent_id', $cat->parent_id)->get();
        $article_list = ArticleModel::where('cat_id', $id)->page();
        return $this->show(compact('cat', 'cat_list', 'article_list'));
    }

    public function helpAction() {
        return $this->sendWithShare()->show();
    }
}