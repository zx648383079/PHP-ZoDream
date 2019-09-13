<?php
namespace Module\Shop\Service;

use Module\Shop\Domain\Models\ArticleCategoryModel;
use Module\Shop\Domain\Models\ArticleModel;

class ArticleController extends Controller {

    public function indexAction($id = null) {
        if ($id > 0) {
            return $this->runMethodNotProcess('detail', compact('id'));
        }
        return $this->show();
    }

    public function detailAction($id) {
        $article = ArticleModel::find($id);
        $category = $article->category;
        $cat_list = ArticleCategoryModel::where('parent_id', $category->parent_id)->all();
        return $this->sendWithShare()->show(compact('article', 'category', 'cat_list'));
    }

    public function categoryAction($id) {
        $cat = ArticleCategoryModel::find($id);
        $cat_list = ArticleCategoryModel::where('parent_id', $cat->parent_id)->all();
        $article_list = ArticleModel::where('cat_id', $id)->page();
        return $this->show(compact('cat', 'cat_list', 'article_list'));
    }

    public function helpAction() {
        return $this->sendWithShare()->show();
    }
}