<?php
declare(strict_types=1);
namespace Module\Code\Service;

use Domain\Model\SearchModel;
use Module\Code\Domain\Model\CommentModel;
use Module\Code\Domain\Repositories\CodeRepository;
use Module\Code\Domain\Repositories\CommentRepository;
use Module\ModuleController;

class CommentController extends ModuleController {

    public function rules() {
        return [
            'index' => '*',
            'save' => '*',
            'more' => '*',
            '*' => '@',
        ];
    }

    public function indexAction(int $id, string $sort = 'created_at', string $order = 'desc') {
        $comment_list = CommentModel::where([
            'code_id' => $id,
            'parent_id' => 0,
        ])->orderBy($sort, $order)->page();
        return $this->show(compact('comment_list', 'id'));
    }

    public function moreAction(int $id, int $parent_id = 0, string $sort = 'created_at', string $order = 'desc') {
        list($sort, $order) = SearchModel::checkSortOrder($sort, $order, ['created_at', 'id']);
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

    public function saveAction(string $content,
                               int $code_id,
                               int $parent_id = 0,
                               bool $is_forward = false) {
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

    public function disagreeAction(int $id) {
        if (!request()->isAjax()) {
            return $this->redirect('./');
        }
        try {
            $model = CommentRepository::disagree($id);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData($model);
    }

    public function agreeAction(int $id) {
        if (!request()->isAjax()) {
            return $this->redirect('./');
        }
        try {
            $model = CommentRepository::agree($id);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData($model);
    }

    public function deleteAction(int $id) {
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
        return $this->redirect([config('auth.home'), 'redirect_uri' => url('./')]);
    }
}