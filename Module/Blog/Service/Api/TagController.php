<?php
namespace Module\Blog\Service\Api;

use Module\Auth\Domain\Concerns\CheckRole;
use Module\Blog\Domain\Model\TagModel;
use Module\Blog\Domain\Model\TagRelationshipModel;
use Module\Blog\Domain\Repositories\TagRepository;

class TagController extends Controller {

    use CheckRole;

    public function rules() {
        return [
            '*' => 'administrator',
            'index' => '*'
        ];
    }

    public function indexAction() {
        return $this->renderData(TagRepository::get());
    }

    public function detailAction(int $id) {
        $model = TagModel::find($id);
        return $this->render($model);
    }

    public function saveAction() {
        $model = new TagModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->render($model);
        }
        return $this->renderFailure($model->getFirstError());
    }

    public function deleteAction(int $id) {
        TagModel::where('id', $id)->delete();
        TagRelationshipModel::where('tag_id', $id)->delete();
        return $this->renderData(true);
    }
}