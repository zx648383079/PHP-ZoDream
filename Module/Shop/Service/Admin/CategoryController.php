<?php
namespace Module\Shop\Service\Admin;


use Module\Shop\Domain\Model\CategoryModel;
use Module\Shop\Domain\Model\GoodsModel;

class CategoryController extends Controller {

    public function indexAction() {
        $model_list = CategoryModel::tree()->makeTreeForHtml();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = CategoryModel::findOrNew($id);
        $cat_list = CategoryModel::where('id', '!=', $id)->all();
        return $this->show(compact('model', 'cat_list'));
    }

    public function saveAction() {
        $model = new CategoryModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->jsonSuccess([
                'url' => $this->getUrl('category')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        CategoryModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('category')
        ]);
    }

    public function refreshAction() {
        CategoryModel::refreshPk(function ($old_id, $new_id) {
            CategoryModel::where('parent_id', $old_id)->update([
                'parent_id' => $new_id
            ]);
            GoodsModel::where('cat_id', $old_id)->update([
                'cat_id' => $new_id
            ]);
        });
        return $this->jsonSuccess([
            'refresh' => true
        ]);
    }
}