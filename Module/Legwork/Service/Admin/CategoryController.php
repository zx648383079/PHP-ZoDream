<?php
namespace Module\Legwork\Service\Admin;

use Module\Legwork\Domain\Model\CategoryModel;

class CategoryController extends Controller {

    public function indexAction($keywords = '') {
        $model_list = CategoryModel::when(!empty($keywords), function ($query) {
            CategoryModel::searchWhere($query, 'name');
            })->orderBy('id', 'desc')->page();
        return $this->show(compact('model_list', 'keywords'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => 0]);
    }

    public function editAction($id) {
        $model = CategoryModel::findOrNew($id);
        if (empty($model)) {
            return $this->redirectWithMessage($this->getUrl('category'), '分类不存在！');
        }
        return $this->show(compact('model'));
    }

    public function saveAction($id = 0) {
        $model = CategoryModel::findOrNew($id);
        if (!$model->load()) {
            return $this->renderFailure($model->getFirstError());
        }
        if (!$model->save()) {
            return $this->renderFailure($model->getFirstError());
        }
        return $this->renderData([
            'url' => $this->getUrl('category')
        ]);
    }

    public function deleteAction($id) {
        CategoryModel::where('id', $id)->delete();
        return $this->renderData([
            'refresh' => true
        ]);
    }
}