<?php
namespace Module\Code\Service;

use Module\Code\Domain\Model\CommentModel;
use Module\Code\Domain\Repositories\CodeRepository;
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
            'code_id' => intval($id),
            'parent_id' => 0,
        ])->orderBy($sort, $order)->page();
        return $this->show(compact('comment_list', 'id'));
    }

    public function moreAction($id, $parent_id = 0, $sort = 'created_at', $order = 'desc') {
        list($sort, $order) = CommentModel::checkSortOrder($sort, $order, ['created_at', 'id']);
        $comment_list = CommentModel::with('replies')
            ->where([
            'code_id' => intval($id),
            'parent_id' => intval($parent_id)
        ])->orderBy($sort, $order)->page();
        if ($parent_id > 0) {
            return $this->show('rely', compact('comment_list', 'parent_id'));
        }
        return $this->show(compact('comment_list'));
    }

    public function saveAction($content,
                               $code_id,
                               $parent_id = 0,
                               $is_forward = false) {
        try {
            $model = CodeRepository::comment($content,
                $code_id,
                $parent_id);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => url('./comment', ['id' => $code_id])
        ]);
    }

    public function disagreeAction($id) {
        if (!request()->isAjax()) {
            return $this->redirect('./');
        }
        try {
            $model = CodeRepository::disagree($id);
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
            $model = CodeRepository::agree($id);
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
            $model = CodeRepository::deleteComment($id);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData($model);
    }

    public function redirectWithAuth() {
        return $this->redirect([Config::auth('home'), 'redirect_uri' => url('./')]);
    }
}