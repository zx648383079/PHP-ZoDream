<?php
namespace Module\Blog\Service\Admin;

use Domain\Model\SearchModel;
use Module\Blog\Domain\Model\TagModel;
use Module\Blog\Domain\Model\TagRelationshipModel;

class TagController extends Controller {

    public function rules() {
        return [
            '*' => 'administrator',
            'index' => '@'
        ];
    }

    public function indexAction(string $keywords = '') {
        $model_list = TagModel::when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, 'name');
            })->orderBy('id', 'desc')->page();
        if (request()->isAjax() && !request()->header('X-PJAX')) {
            return $this->renderData($model_list);
        }
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->editAction(0);
    }

    public function editAction(int $id) {
        $model = TagModel::findOrNew($id);
        return $this->show('edit', compact('model'));
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

    public function deleteAction(int $id) {
        TagModel::where('id', $id)->delete();
        TagRelationshipModel::where('tag_id', $id)->delete();
        return $this->renderData([
            'url' => $this->getUrl('tag')
        ]);
    }
}