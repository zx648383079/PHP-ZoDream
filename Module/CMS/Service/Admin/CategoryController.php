<?php
namespace Module\CMS\Service\Admin;

use Module\CMS\Domain\Model\CategoryModel;
use Module\CMS\Domain\Model\GroupModel;
use Module\CMS\Domain\Model\ModelModel;

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
        if (!$model->position) {
            $model->position = 99;
        }
        $model_list = ModelModel::where('type', 0)->select('name', 'id')->all();
        $group_list = GroupModel::where('type', 0)->all();
        $cat_list = CategoryModel::tree()->makeTreeForHtml();
        if (!empty($id)) {
            $excludes = [$id];
            $cat_list = array_filter($cat_list, function ($item) use (&$excludes) {
                if (in_array($item['id'], $excludes)) {
                    return false;
                }
                if (in_array($item['parent_id'], $excludes)) {
                    $excludes[] = $item['id'];
                    return false;
                }
                return true;
            });
        }
        return $this->show(compact('model', 'model_list', 'cat_list', 'group_list'));
    }

    public function saveAction($id) {
        $model = new CategoryModel();
        if (!$model->load() || !$model->autoIsNew()->save()) {
            return $this->jsonFailure($model->getFirstError());
        }
        return $this->jsonSuccess([
            'url' => $this->getUrl('category')
        ]);
    }

    public function deleteAction($id) {
        CategoryModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('category')
        ]);
    }
}