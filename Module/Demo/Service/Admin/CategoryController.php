<?php
namespace Module\Demo\Service\Admin;

use Module\Demo\Domain\Model\CategoryModel;

class CategoryController extends Controller {

    public function indexAction($keywords = null) {
        $items = CategoryModel::tree()->makeTreeForHtml();
        return $this->show(compact('items'));
    }

    public function createAction() {
        return $this->editAction(0);
    }

    public function editAction($id) {
        $model = CategoryModel::findOrNew($id);
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
        return $this->show('edit', compact('model', 'cat_list'));
    }

    public function saveAction() {
        $model = new CategoryModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->renderData([
                'url' => $this->getUrl('category')
            ]);
        }
        return $this->renderFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        CategoryModel::where('id', $id)->delete();
        return $this->renderData([
            'url' => $this->getUrl('category')
        ]);
    }
}