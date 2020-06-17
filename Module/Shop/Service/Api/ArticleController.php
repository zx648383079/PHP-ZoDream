<?php
namespace Module\Shop\Service\Api;

use Module\Shop\Domain\Models\ArticleCategoryModel;
use Module\Shop\Domain\Models\ArticleModel;

class ArticleController extends Controller {

    public function indexAction($id = 0, $category = 0, $keywords = null) {
        if ($id > 0) {
            return $this->runMethodNotProcess('detail', compact('id'));
        }
        $model_list = ArticleModel::with('category')
            ->when($category > 0, function ($query) use ($category) {
                $query->where('cat_id', $category);
            })
            ->when(!empty($keywords), function ($query) {
                ArticleModel::searchWhere($query, 'title');
            })
            ->select(ArticleModel::THUMB_MODE)->page();
        return $this->renderPage($model_list);
    }

    public function detailAction($id) {
        $article = ArticleModel::find($id);
        return $this->render($article);
    }

    public function categoryAction($parent_id = 0) {
        $cat_list = ArticleCategoryModel::where('parent_id', $parent_id)->all();
        return $this->render($cat_list);
    }

    public function suggestAction($keywords) {
        $data = ArticleModel::when(!empty($keywords), function ($query) {
            ArticleModel::searchWhere($query, 'title');
        })->limit(4)->get('id', 'title');
        return $this->render(compact('data'));
    }
}