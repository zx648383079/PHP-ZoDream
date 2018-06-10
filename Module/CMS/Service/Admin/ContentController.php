<?php
namespace Module\CMS\Service\Admin;

use Module\CMS\Domain\Model\CategoryModel;
use Module\CMS\Domain\Model\ContentModel;
use Module\CMS\Domain\Model\ModelFieldModel;
use Module\CMS\Domain\Model\ModelModel;

class ContentController extends Controller {
    public function indexAction($cat_id) {
        $cat = CategoryModel::find($cat_id);
        $model_list = ContentModel::with('category')->where('cat_id', $cat_id)->page();
        return $this->show(compact('model_list', 'cat'));
    }

    public function createAction() {
        return $this->runMethod('edit', ['id' => null]);
    }

    public function editAction($id, $cat_id = 0) {
        $model = ContentModel::findOrNew($id);
        if (!$model->cat_id) {
            $model->cat_id = $cat_id;
        }
        $field_list = ModelFieldModel::where('model_id', $model->category->model_id)->all();
        return $this->show(compact('model', 'model_list', 'field_list'));
    }

    public function saveAction() {
        $model = new ContentModel();
        if (!$model->load() || !$model->autoIsNew()->save()) {
            return $this->jsonFailure($model->getFirstError());
        }
        return $this->jsonSuccess([
            'url' => $this->getUrl('content')
        ]);
    }

    public function deleteAction($id) {
        ContentModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('content')
        ]);
    }
}