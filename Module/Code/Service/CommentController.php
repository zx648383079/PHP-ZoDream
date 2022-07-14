<?php
declare(strict_types=1);
namespace Module\Code\Service;

use Module\Code\Domain\Repositories\CodeRepository;
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

    public function indexAction(int $id, $sort = 'created_at', $order = 'desc') {
        $comment_list = CodeRepository::comment()
            ->search('', 0, $id, 0, $sort, $order);
        return $this->show(compact('comment_list', 'id'));
    }

    public function moreAction(int $id, int $parent_id = 0, $sort = 'created_at', $order = 'desc') {
        $comment_list = CodeRepository::comment()
            ->search('', 0, $id, $parent_id, $sort, $order);
        if ($parent_id > 0) {
            return $this->show('rely', compact('comment_list', 'parent_id'));
        }
        return $this->show(compact('comment_list'));
    }

    public function saveAction($content,
                               int $code_id,
                               int $parent_id = 0) {
        try {
            $model = CodeRepository::commentSave($content,
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
            $model = CodeRepository::comment()->disagree($id);
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
            $model = CodeRepository::comment()->agree($id);
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