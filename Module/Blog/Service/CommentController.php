<?php
namespace Module\Blog\Service;

use Domain\Model\SearchModel;
use Module\Blog\Domain\Model\BlogModel;
use Module\Blog\Domain\Model\CommentModel;
use Module\Blog\Domain\Repositories\CommentRepository;
use Module\ModuleController;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class CommentController extends ModuleController {

    public function rules() {
        return [
            'index' => '*',
            'save' => '*',
            'more' => '*',
            '*' => '@',
        ];
    }

    public function indexAction(int $blog_id) {
        $hot_comments = CommentRepository::getHot($blog_id, 4);
        return $this->show(compact('hot_comments', 'blog_id'));
    }

    public function moreAction(int $blog_id, int $parent_id = 0,
                               string $sort = 'created_at', string $order = 'desc') {
        list($sort, $order) = SearchModel::checkSortOrder($sort, $order, ['created_at', 'id']);
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
        return $this->renderData($comment);
    }

    public function disagreeAction(int $id) {
        if (!request()->isAjax()) {
            return $this->redirect('./');
        }
        try {
            $model = CommentRepository::disagree($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData($model->disagree_count);
    }

    public function agreeAction(int $id) {
        if (!request()->isAjax()) {
            return $this->redirect('./');
        }
        try {
            $model = CommentRepository::agree($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData($model->agree_count);
    }

    public function reportAction(int $id) {
        try {
            CommentRepository::report($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}