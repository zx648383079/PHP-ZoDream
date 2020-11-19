<?php
namespace Module\Shop\Service\Admin;


use Module\Shop\Domain\Models\ArticleCategoryModel;
use Module\Shop\Domain\Models\ArticleModel;

class ArticleController extends Controller {

    public function indexAction($keywords = null, $cat_id = 0) {
        $model_list = ArticleModel::with('category')
            ->when(!empty($keywords), function ($query) {
                $query->where(function ($query) {
                    ArticleModel::search($query, 'title');
                });
            })->when(!empty($cat_id), function ($query) use ($cat_id) {
                $query->where('cat_id', intval($cat_id));
            })->page();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = ArticleModel::findOrNew($id);
        $cat_list = ArticleCategoryModel::all();
        return $this->show(compact('model', 'cat_list'));
    }

    public function saveAction() {
        $model = new ArticleModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->renderData([
                'url' => $this->getUrl('article')
            ]);
        }
        return $this->renderFailure($model->getFirstError());
    }


    public function categoryAction() {
        $model_list = ArticleCategoryModel::all();
        return $this->show(compact('model_list'));
    }

    public function createCategoryAction() {
        return $this->runMethodNotProcess('editCategory', ['id' => null]);
    }

    public function editCategoryAction($id, $parent_id = 0) {
        $cat_list = ArticleCategoryModel::all();
        $model = ArticleCategoryModel::findOrNew($id);
        return $this->show(compact('cat_list', 'model'));
    }

    public function saveCategoryAction() {
        $model = new ArticleCategoryModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->renderData([
                'url' => $this->getUrl('article/category')
            ]);
        }
        return $this->renderFailure($model->getFirstError());
    }

    public function deleteCategoryAction($id) {
        ArticleCategoryModel::where('id', $id)->delete();
        return $this->renderData([
            'url' => $this->getUrl('article/category')
        ]);
    }
}