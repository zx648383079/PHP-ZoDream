<?php
namespace Module\Shop\Service\Mobile;

use Module\Shop\Domain\Model\ArticleCategoryModel;
use Module\Shop\Domain\Model\ArticleModel;

class ArticleController extends Controller {

    public function indexAction($id = null) {
        if ($id > 0) {
            return $this->runMethodNotProcess('detail');
        }
        $model_list = ArticleModel::page();
        return $this->show(compact('model_list'));
    }

    public function detailAction($id) {
        $article = ArticleModel::find($id);
        $cat = $article->category;
        $cat_list = ArticleCategoryModel::where('parent_id', $cat->parent_id)->all();
        return $this->show(compact('article', 'cat', 'cat_list'));
    }

    public function categoryAction($id) {
        $cat = ArticleCategoryModel::find($id);
        $cat_list = ArticleCategoryModel::where('parent_id', $cat->parent_id)->all();
        $article_list = ArticleModel::where('cat_id', $id)->page();
        return $this->show(compact('cat', 'cat_list', 'article_list'));
    }
}