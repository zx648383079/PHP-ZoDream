<?php
namespace Module\Blog\Service\Admin;

use Module\Blog\Domain\Model\BlogModel;
use Module\Blog\Domain\Model\TagRelationshipModel;
use Module\Blog\Domain\Model\TermModel;


class BlogController extends Controller {

    public function indexAction($keywords = null, $term_id = null) {
        $blog_list = BlogModel::with('term')
            ->where('user_id', auth()->id())
            ->when(!empty($keywords), function ($query) {
                $query->where(function ($query) {
                    BlogModel::search($query, 'title');
                });
            })->when(!empty($term_id), function ($query) use ($term_id) {
                $query->where('term_id', intval($term_id));
            })->orderBy('id', 'desc')->select('id', 'title', 'term_id', 'comment_count', 'recommend')->page();
        $term_list = TermModel::select('id', 'name')->all();
        return $this->show(compact('blog_list', 'term_list'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = BlogModel::findOrNew($id);
        if (!$model->isNewRecord && $model->user_id != auth()->id()) {
            return $this->redirectWithMessage($this->getUrl('blog'), '博客不存在！');
        }
        $term_list = TermModel::select('id', 'name')->all();
        $tags = $model->isNewRecord ? [] : TagRelationshipModel::where('blog_id', $id)->pluck('tag_id');
        return $this->show(compact('model', 'term_list', 'tags'));
    }

    public function saveAction($id = null) {
        $model = BlogModel::findOrNew($id);
        $model->user_id = auth()->id();
        $model->comment_status = 0;
        $isNew = $model->isNewRecord;
        if (!$model->load(null, ['user_id']) || !$model->save()) {
            return $this->jsonFailure($model->getFirstError());
        }
        TagRelationshipModel::bind($model->id, app('request')->get('tag'), $isNew);
        return $this->jsonSuccess([
            'url' => $this->getUrl('blog')
        ]);
    }

    public function deleteAction($id) {
        BlogModel::where('id', $id)->where('user_id', auth()->id())->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('blog')
        ]);
    }
}