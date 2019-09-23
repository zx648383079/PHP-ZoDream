<?php
namespace Module\MicroBlog\Service;

use Module\MicroBlog\Domain\Model\CommentModel;
use Module\ModuleController;

class CommentController extends ModuleController {

    protected function rules() {
        return [
            'index' => '*',
            'save' => '*',
            'more' => '*',
            '*' => '@',
        ];
    }

    public function indexAction($id, $sort = 'created_at', $order = 'desc') {
        $comment_list = CommentModel::where([
            'micro_id' => intval($id),
            'parent_id' => 0,
        ])->orderBy($sort, $order)->page();
        return $this->show(compact('comment_list', 'id'));
    }

    public function moreAction($id, $parent_id = 0, $sort = 'created_at', $order = 'desc') {
        list($sort, $order) = CommentModel::checkSortOrder($sort, $order, ['created_at', 'id']);
        $comment_list = CommentModel::with('replies')
            ->where([
            'micro_id' => intval($id),
            'parent_id' => intval($parent_id)
        ])->orderBy($sort, $order)->page();
        if ($parent_id > 0) {
            return $this->show('rely', compact('comment_list', 'parent_id'));
        }
        return $this->show(compact('comment_list'));
    }

    public function saveAction() {
        $data = app('request')->get('content,parent_id,micro_id');
        $data['user_id'] = auth()->id();
        $data['parent_id'] = intval($data['parent_id']);
        $model = CommentModel::create($data);
        if (empty($model)) {
            return $this->jsonFailure('评论失败！');
        }
        return $this->jsonSuccess([
            'url' => url('./comment', ['id' => $data['micro_id']])
        ]);
    }

    public function disagreeAction($id) {
        if (!app('request')->isAjax()) {
            return $this->redirect('./');
        }
        $id = intval($id);
        if (!CommentModel::canAgree($id)) {
            return $this->jsonFailure('一个用户只能操作一次！');
        }
        $model = CommentModel::find($id);
        $model->agreeThis(false);
        return $this->jsonSuccess($model->disagree);
    }

    public function agreeAction($id) {
        if (!app('request')->isAjax()) {
            return $this->redirect('./');
        }
        $id = intval($id);
        if (!CommentModel::canAgree($id)) {
            return $this->jsonFailure('一个用户只能操作一次！');
        }
        $model = CommentModel::find($id);
        $model->agreeThis();
        return $this->jsonSuccess($model->agree);
    }

    public function reportAction($id) {

    }

    public function logAction() {
        CommentModel::alias('c');
    }
}