<?php
namespace Module\Blog\Service\Admin;

use Module\Blog\Domain\Model\BlogModel;
use Module\Blog\Domain\Model\TermModel;
use Zodream\Domain\Access\Auth;

class BlogController extends Controller {

    public function indexAction($keywords = null, $term_id = null) {
        $blog_list = BlogModel::with('term')
            ->when(!empty($keywords), function ($query) {
                $query->where(function ($query) {
                    BlogModel::search($query, 'title');
                });
            })->when(!empty($term_id), function ($query) use ($term_id) {
                $query->where('term_id', intval($term_id));
            })->order('id', 'desc')->select('id', 'title', 'term_id', 'comment_count', 'recommend')->page();
        return $this->show(compact('blog_list'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = BlogModel::findOrNew($id);
        $term_list = TermModel::select('id', 'name')->all();
        return $this->show(compact('model', 'term_list'));
    }

    public function saveAction() {
        $model = new BlogModel();
        $model->user_id = Auth::id();
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