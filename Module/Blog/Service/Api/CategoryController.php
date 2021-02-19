<?php
namespace Module\Blog\Service\Api;

use Module\Auth\Domain\Concerns\CheckRole;
use Module\Blog\Domain\Model\TermModel;
use Module\Blog\Domain\Repositories\CategoryRepository;

class CategoryController extends Controller {

    use CheckRole;

    public function rules() {
        return [
            '*' => 'administrator',
            'index' => '*'
        ];
    }

    public function indexAction() {
        return $this->renderData(CategoryRepository::get());
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

    public function allAction() {
        return $this->renderData(CategoryRepository::all());
    }
}