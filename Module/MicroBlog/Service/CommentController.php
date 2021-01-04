<?php
namespace Module\MicroBlog\Service;

use Module\MicroBlog\Domain\Model\CommentModel;
use Module\MicroBlog\Domain\Repositories\MicroRepository;
use Module\ModuleController;
use Zodream\Service\Config;

class CommentController extends ModuleController {

    public function rules() {
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

    public function saveAction($content,
                               $micro_id,
                               $parent_id = 0,
                               $is_forward = false) {
        try {
            $model = MicroRepository::comment($content,
                $micro_id,
                $parent_id,
                $is_forward);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => url('./comment', ['id' => $micro_id])
        ]);
    }

    public function disagreeAction($id) {
        if (!request()->isAjax()) {
            return $this->redirect('./');
        }
        try {
            $model = MicroRepository::disagree($id);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData($model);
    }

    public function agreeAction($id) {
        if (!request()->isAjax()) {
            return $this->redirect('./');
        }
        try {
            $model = MicroRepository::agree($id);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData($model);
    }

    public function deleteAction($id) {
        if (!request()->isAjax()) {
            return $this->redirect('./');
        }
        try {
            $model = MicroRepository::deleteComment($id);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData($model);
    }


    public function redirectWithAuth() {
        return $this->redirect([Config::auth('home'), 'redirect_uri' => url('./')]);
    }
}