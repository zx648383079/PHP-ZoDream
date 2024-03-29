<?php
namespace Module\Blog\Service\Admin;

use Module\Blog\Domain\Model\TermModel;

class TermController extends Controller {

    public function rules() {
        return [
            '*' => 'administrator',
            'index' => '@'
        ];
    }

    public function indexAction(string $keywords = '') {
        $term_list = TermModel::withCount('blog')->orderBy('id', 'desc')->all();
        return $this->show(compact('term_list'));
    }

    public function createAction() {
        return $this->editAction(0);
    }

    public function editAction(int $id) {
        $model = TermModel::findOrNew($id);
        return $this->show('edit', compact('model'));
    }

    public function saveAction() {
        $model = new TermModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->renderData([
                'url' => $this->getUrl('term')
            ]);
        }
        return $this->renderFailure($model->getFirstError());
    }

    public function deleteAction(int $id) {
        TermModel::where('id', $id)->delete();
        return $this->renderData([
            'url' => $this->getUrl('term')
        ]);
    }
}