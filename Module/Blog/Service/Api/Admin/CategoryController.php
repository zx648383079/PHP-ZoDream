<?php
namespace Module\Blog\Service\Api\Admin;

use Module\Auth\Domain\Concerns\AdminRole;
use Module\Blog\Domain\Model\TermModel;
use Zodream\Route\Controller\RestController;

class CategoryController extends RestController {

    use AdminRole;

    public function indexAction($keywords = null) {
        $term_list = TermModel::withCount('blog')
            ->orderBy('id', 'desc')->all();
        return $this->renderData($term_list);
    }

    public function detailAction($id) {
        $model = TermModel::findOrNew($id);
        return $this->render($model);
    }

    public function saveAction() {
        $model = new TermModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->render($model);
        }
        return $this->renderFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        TermModel::where('id', $id)->delete();
        return $this->renderData(true);
    }
}