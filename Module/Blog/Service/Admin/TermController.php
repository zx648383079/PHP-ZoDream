<?php
namespace Module\Blog\Service\Admin;

use Module\Blog\Domain\Model\TermModel;

class TermController extends Controller {

    protected function rules() {
        return [
            '*' => 'administrator',
            'index' => '@'
        ];
    }

    public function indexAction($keywords = null) {
        $term_list = TermModel::withCount('blog')->orderBy('id', 'desc')->all();
        return $this->show(compact('term_list'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = TermModel::findOrNew($id);
        return $this->show(compact('model'));
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

    public function deleteAction($id) {
        TermModel::where('id', $id)->delete();
        return $this->renderData([
            'url' => $this->getUrl('term')
        ]);
    }
}