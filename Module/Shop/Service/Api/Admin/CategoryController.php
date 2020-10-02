<?php
namespace Module\Shop\Service\Api\Admin;


use Module\Shop\Domain\Models\CategoryModel;
use Module\Shop\Domain\Models\GoodsModel;
use Zodream\Html\Tree;

class CategoryController extends Controller {

    public function indexAction() {
        $model_list = CategoryModel::tree()->makeTreeForHtml();
        return $this->renderData($model_list);
    }

    public function detailAction($id) {
        $model = CategoryModel::find($id);
        return $this->render($model);
    }

    public function saveAction() {
        $model = new CategoryModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->render($model);
        }
        return $this->renderFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        CategoryModel::where('id', $id)->delete();
        return $this->renderData(true);
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
        return $this->renderData(true);
    }

    public function allAction() {
        return $this->renderData(
            (new Tree(CategoryModel::query()->get('id', 'name', 'parent_id')))
            ->makeTreeForHtml()
        );
    }

}