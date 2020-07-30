<?php
namespace Module\Blog\Service\Api;

use Module\Blog\Domain\Model\TermModel;
use Module\Blog\Domain\Repositories\TermRepository;
use Zodream\Route\Controller\RestController;

class TermController extends RestController {

    protected function rules() {
        return [
            '*' => 'administrator',
            'index' => '*'
        ];
    }

    public function indexAction() {
        return $this->renderData(TermRepository::get());
    }

    public function detailAction($id) {
        $model = TermModel::find($id);
        return $this->renderData($model);
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