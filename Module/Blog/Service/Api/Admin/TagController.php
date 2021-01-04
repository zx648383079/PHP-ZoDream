<?php
namespace Module\Blog\Service\Api\Admin;

use Module\Blog\Domain\Model\TagModel;
use Module\Blog\Domain\Model\TagRelationshipModel;

class TagController extends Controller {

    public function indexAction($keywords = null) {
        $model_list = TagModel::when(!empty($keywords), function ($query) {
                TagModel::searchWhere($query, 'name');
            })->orderBy('id', 'desc')->page();
        return $this->renderPage($model_list);
    }

    public function detailAction($id) {
        $model = TagModel::findOrNew($id);
        return $this->render($model);
    }

    public function saveAction() {
        $model = new TagModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->render($model);
        }
        return $this->renderFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        TagModel::where('id', $id)->delete();
        TagRelationshipModel::where('tag_id', $id)->delete();
        return $this->renderData(true);
    }

    public function allAction() {
        return $this->renderData(
            TagModel::query()->get('id', 'name')
        );
    }
}