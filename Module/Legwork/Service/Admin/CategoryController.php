<?php
declare(strict_types=1);
namespace Module\Legwork\Service\Admin;

use Domain\Model\SearchModel;
use Module\Legwork\Domain\Model\CategoryModel;

class CategoryController extends Controller {

    public function indexAction(string $keywords = '') {
        $items = CategoryModel::when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, 'name');
            })->orderBy('id', SORT_DESC)->page();
        return $this->show(compact('items', 'keywords'));
    }

    public function createAction() {
        return $this->editAction(0);
    }

    public function editAction(int $id) {
        $model = CategoryModel::findOrNew($id);
        if (empty($model)) {
            return $this->redirectWithMessage($this->getUrl('category'), '分类不存在！');
        }
        return $this->show('edit', compact('model'));
    }

    public function saveAction(int $id = 0) {
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

    public function deleteAction(int $id) {
        CategoryModel::where('id', $id)->delete();
        return $this->renderData([
            'refresh' => true
        ]);
    }
}