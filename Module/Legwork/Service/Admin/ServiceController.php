<?php
namespace Module\Legwork\Service\Admin;

use Module\Legwork\Domain\Model\CategoryModel;
use Module\Legwork\Domain\Model\ServiceModel;

class ServiceController extends Controller {

    public function indexAction($keywords = '', $cat_id = 0) {
        $model_list = ServiceModel::with('category')->when(!empty($keywords), function ($query) {
                ServiceModel::searchWhere($query, 'name');
            })->when($cat_id > 0, function ($query) use ($cat_id) {
                $query->where('cat_id', $cat_id);
        })->orderBy('id', 'desc')->page();
        $cat_list = CategoryModel::query()->get();
        return $this->show(compact('model_list', 'keywords', 'cat_list', 'cat_id'));
    }

    public function createAction() {
        return $this->editAction(0);
    }

    public function editAction($id) {
        $model = ServiceModel::findOrNew($id);
        if (empty($model)) {
            return $this->redirectWithMessage($this->getUrl('service'), '服务不存在！');
        }
        $cat_list = CategoryModel::query()->get();
        return $this->show('edit', compact('model', 'cat_list'));
    }

    public function saveAction($id = 0, $form = []) {
        $model = ServiceModel::findOrNew($id);
        if (!$model->load()) {
            return $this->renderFailure($model->getFirstError());
        }
        $model->form = self::formArr($form, null, function ($item) {
            return !empty($item['name']);
        });
        if (!$model->save()) {
            return $this->renderFailure($model->getFirstError());
        }
        return $this->renderData([
            'url' => $this->getUrl('service')
        ]);
    }

    public function deleteAction($id) {
        ServiceModel::where('id', $id)->delete();
        return $this->renderData([
            'refresh' => true
        ]);
    }
}