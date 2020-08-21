<?php
namespace Module\Demo\Service\Admin;

use Module\Demo\Domain\Model\TagModel;
use Module\Demo\Domain\Model\TagRelationshipModel;

class TagController extends Controller {

    public function indexAction($keywords = null) {
        $model_list = TagModel::when(!empty($keywords), function ($query) {
                TagModel::searchWhere($query, 'name');
            })->orderBy('id', 'desc')->page();
        if (app('request')->isAjax() && !app('request')->header('X-PJAX')) {
            return $this->jsonSuccess($model_list);
        }
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = TagModel::findOrNew($id);
        return $this->show(compact('model'));
    }

    public function saveAction() {
        $model = new TagModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->jsonSuccess([
                'url' => $this->getUrl('tag')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        TagModel::where('id', $id)->delete();
        TagRelationshipModel::where('tag_id', $id)->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('tag')
        ]);
    }
}