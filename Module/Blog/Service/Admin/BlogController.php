<?php
namespace Module\Blog\Service\Admin;

use Module\Blog\Domain\Model\BlogModel;
use Module\Blog\Domain\Model\TermModel;

class BlogController extends Controller {

    public function indexAction($keywords = null) {
        $blog_list = BlogModel::with('term')->when(!empty($keywords), function ($query) {
            BlogModel::search($query, 'title');
        })->order('id', 'desc')->select('id', 'title')->page();
        return $this->show(compact('blog_list'));
    }

    public function createAction() {
        return $this->runMethod('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = BlogModel::findOrNew($id);
        $term_list = TermModel::select('id', 'name')->all();
        return $this->show(compact('model', 'term_list'));
    }

    public function saveAction() {
        $model = new BlogModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->jsonSuccess([
                'url' => $this->getUrl('blog')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        BlogModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('blog')
        ]);
    }
}