<?php
namespace Module\Blog\Service\Admin;

use Module\Blog\Domain\Model\TagModel;
use Module\Blog\Domain\Model\TagRelationshipModel;

class TagController extends Controller {

    protected function rules() {
        return [
            '*' => 'administrator',
            'index' => '@'
        ];
    }

    public function indexAction($keywords = null) {
        $model_list = TagModel::when(!empty($keywords), function ($query) {
                TagModel::searchWhere($query, 'name');
            })->orderBy('id', 'desc')->page();
        if (app('request')->isAjax() && !app('request')->header('X-PJAX')) {
            return $this->renderData($model_list);
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
            return $this->renderData([
                'url' => $this->getUrl('tag')
            ]);
        }
        return $this->renderFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        TagModel::where('id', $id)->delete();
        TagRelationshipModel::where('tag_id', $id)->delete();
        return $this->renderData([
            'url' => $this->getUrl('tag')
        ]);
    }
}