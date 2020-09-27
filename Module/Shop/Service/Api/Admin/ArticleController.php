<?php
namespace Module\Shop\Service\Api\Admin;


use Module\Shop\Domain\Models\ArticleCategoryModel;
use Module\Shop\Domain\Models\ArticleModel;
use Zodream\Html\Tree;

class ArticleController extends Controller {

    public function indexAction($keywords = '', $cat_id = 0) {
        $model_list = ArticleModel::with('category')
            ->when(!empty($keywords), function ($query) {
                $query->where(function ($query) {
                    ArticleModel::search($query, 'title');
                });
            })->when(!empty($cat_id), function ($query) use ($cat_id) {
                $query->where('cat_id', intval($cat_id));
            })->page();
        return $this->renderPage($model_list);
    }

    public function detailAction($id) {
        $model = ArticleModel::find($id);
        return $this->render($model);
    }

    public function saveAction() {
        $model = new ArticleModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->render($model);
        }
        return $this->renderFailure($model->getFirstError());
    }


    public function categoryAction() {
        $model_list = ArticleCategoryModel::query()->page();
        return $this->renderPage($model_list);
    }

    public function detailCategoryAction($id) {
        $model = ArticleCategoryModel::find($id);
        return $this->render($model);
    }

    public function saveCategoryAction() {
        $model = new ArticleCategoryModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->render($model);
        }
        return $this->renderFailure($model->getFirstError());
    }

    public function deleteCategoryAction($id) {
        ArticleCategoryModel::where('id', $id)->delete();
        return $this->renderData(true);
    }

    public function categoryTreeAction() {
        $tree = new Tree(ArticleCategoryModel::query()
            ->orderBy('position', 'asc')->get('id', 'name', 'parent_id'));
        return $this->renderData($tree->makeTreeForHtml());
    }
}