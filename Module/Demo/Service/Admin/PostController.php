<?php
namespace Module\Demo\Service\Admin;

use Domain\Model\SearchModel;
use Module\Demo\Domain\Model\PostModel;
use Module\Demo\Domain\Model\TagRelationshipModel;
use Module\Demo\Domain\Model\CategoryModel;
use Module\Demo\Domain\Repositories\PostRepository;
use Module\Demo\Domain\Repositories\TagRepository;

class PostController extends Controller {

    public function indexAction($keywords = null, $cat_id = null) {
        $post_list = PostModel::with('category')
            ->where('user_id', auth()->id())
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'title');
            })->when(!empty($cat_id), function ($query) use ($cat_id) {
                $query->where('cat_id', intval($cat_id));
            })->orderBy('id', 'desc')->page();
        $cat_list = CategoryModel::tree()->makeTreeForHtml();
        return $this->show(compact('post_list', 'cat_list', 'keywords', 'cat_id'));
    }

    public function createAction() {
        return $this->editAction(0);
    }

    public function editAction($id) {
        $model = PostModel::findOrNew($id);
        if (empty($model) || (!$model->isNewRecord && $model->user_id != auth()->id())) {
            return $this->redirectWithMessage($this->getUrl('post'), '文章不存在！');
        }
        $cat_list = CategoryModel::tree()->makeTreeForHtml();
        $tags = $model->isNewRecord ? [] : TagRepository::getTags($model->id);
        return $this->show('edit', compact('model', 'cat_list', 'tags'));
    }

    public function saveAction($id = null) {
        $model = PostModel::findOrNew($id);
        $isNew = $model->isNewRecord;
        if (!$model->load(null, ['user_id'])) {
            return $this->renderFailure($model->getFirstError());
        }
        $model->user_id = auth()->id();
        $file = PostRepository::file($model);
        if ($file->exist()) {
            $model->size = $file->size();
        }
        if (!$model->saveIgnoreUpdate()) {
            return $this->renderFailure($model->getFirstError());
        }
        TagRelationshipModel::bind($model->id, request()->get('tag', []), $isNew);
        PostRepository::unzipFile($model);
        return $this->renderData([
            'url' => $isNew ? $this->getUrl('post') : -1
        ]);
    }

    public function deleteAction($id) {
        $model = PostModel::where('id', $id)->where('user_id', auth()->id());
        if (empty($model)) {
            return $this->renderFailure('文章不存在');
        }
        $model->delete();
        return $this->renderData([
            'refresh' => true
        ]);
    }

    public function uploadAction() {
        try {
            $file = PostRepository::saveFile();
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData($file);
    }
}