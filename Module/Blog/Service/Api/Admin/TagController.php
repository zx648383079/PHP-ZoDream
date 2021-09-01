<?php
declare(strict_types=1);
namespace Module\Blog\Service\Api\Admin;

use Domain\Model\SearchModel;
use Module\Blog\Domain\Model\TagModel;
use Module\Blog\Domain\Model\TagRelationshipModel;
use Module\Blog\Domain\Repositories\TagRepository;

class TagController extends Controller {

    public function indexAction(string $keywords = '') {
        return $this->renderPage(
            TagRepository::getList($keywords)
        );
    }

    public function detailAction(int $id) {
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

    public function deleteAction(int $id) {
        TagRepository::remove($id);
        return $this->renderData(true);
    }

    public function allAction() {
        return $this->renderData(
            TagRepository::allList()
        );
    }
}