<?php
namespace Module\Blog\Service\Admin;

use Module\Blog\Domain\Model\BlogModel;
use Module\Blog\Domain\Model\BlogPageModel;
use Module\Blog\Domain\Model\TagRelationshipModel;
use Module\Blog\Domain\Model\TermModel;
use Module\Blog\Domain\Events\BlogUpdate;

class BlogController extends Controller {

    public function indexAction($keywords = null, $term_id = null) {
        $blog_list = BlogPageModel::with('term')
            ->where('user_id', auth()->id())
            ->when(!empty($keywords), function ($query) {
                BlogModel::searchWhere($query, 'title');
            })->when(!empty($term_id), function ($query) use ($term_id) {
                $query->where('term_id', intval($term_id));
            })->orderBy('id', 'desc')->page();
        $term_list = TermModel::select('id', 'name')->all();
        return $this->show(compact('blog_list', 'term_list', 'keywords', 'term_id'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => null]);
    }

    public function editAction($id, $language = null) {
        $model = BlogModel::getOrNew($id, $language);
        if (empty($model) || (!$model->isNewRecord && $model->user_id != auth()->id())) {
            return $this->redirectWithMessage($this->getUrl('blog'), '博客不存在！');
        }
        $term_list = TermModel::select('id', 'name')->all();
        $tags = $model->isNewRecord ? [] : TagRelationshipModel::where('blog_id', $id)->pluck('tag_id');
        return $this->show(compact('model', 'term_list', 'tags'));
    }

    public function saveAction($id = null) {
        $model = BlogModel::findOrNew($id);
        $isNew = $model->isNewRecord;
        if (!$model->load(null, ['user_id'])) {
            return $this->jsonFailure($model->getFirstError());
        }
        // 需要同步的字段
        $async_column = [
            'user_id',
            'term_id',
            'programming_language',
            'type',
            'thumb',
            'open_type',
            'open_rule',
            'source_url',
            'comment_status'];
        $model->user_id = auth()->id();
        $model->comment_status = 0;
        if ($model->parent_id > 0) {
            $parent = BlogModel::find($model->parent_id);
            if (empty($model->language) || $model->language == 'zh') {
                $model->language = 'en';
            }
            foreach ($async_column as $key) {
                $model->{$key} = $parent->{$key};
            }
        } 
        $model->parent_id = intval($model->parent_id);
        if (!$model->saveIgnoreUpdate()) {
            return $this->jsonFailure($model->getFirstError());
        }
        if ($model->parent_id < 1) {
            TagRelationshipModel::bind($model->id, app('request')->get('tag', []), $isNew);
            $data = [];
            foreach ($async_column as $key) {
                $data[$key] = $model->getAttributeSource($key);
            }
            BlogModel::where('parent_id', $model->id)->update($data);
        }
        event(new BlogUpdate($model, time()));
        return $this->jsonSuccess([
            'url' => $isNew ? $this->getUrl('blog') : -1
        ]);
    }

    public function deleteAction($id) {
        $model = BlogModel::where('id', $id)->where('user_id', auth()->id());
        if (empty($model)) {
            return $this->jsonFailure('文章不存在');
        }
        $model->delete();
        if ($model->parent_id < 1) {
            BlogModel::where('parent_id', $id)->delete();
        }
        return $this->jsonSuccess([
            'refresh' => true
        ]);
    }
}