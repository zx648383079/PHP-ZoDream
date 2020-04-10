<?php
namespace Module\Blog\Service;

use Module\Blog\Domain\Model\BlogModel;
use Module\Blog\Domain\Model\CommentModel;
use Module\Blog\Domain\Repositories\CommentRepository;
use Module\ModuleController;
use Zodream\Infrastructure\Http\Request;

class CommentController extends ModuleController {

    protected function rules() {
        return [
            'index' => '*',
            'save' => '*',
            'more' => '*',
            '*' => '@',
        ];
    }

    public function indexAction($blog_id) {
        $hot_comments = CommentRepository::getHot($blog_id, 4);
        return $this->show(compact('hot_comments', 'blog_id'));
    }

    public function moreAction($blog_id, $parent_id = 0, $sort = 'created_at', $order = 'desc') {
        list($sort, $order) = CommentModel::checkSortOrder($sort, $order, ['created_at', 'id']);
        $comment_list = CommentModel::with('replies')
            ->where([
            'blog_id' => intval($blog_id),
            'parent_id' => intval($parent_id)
        ])->orderBy($sort, $order)->page();
        if ($parent_id > 0) {
            return $this->show('rely', compact('comment_list', 'parent_id'));
        }
        return $this->show(compact('comment_list'));
    }

    public function saveAction(Request $request) {
        try {
            $comment = CommentRepository::create($request->get('name,email,url,content,parent_id,blog_id'));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->jsonSuccess($comment);
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