<?php
declare(strict_types=1);
namespace Module\MicroBlog\Service;

use Module\MicroBlog\Domain\Repositories\MicroRepository;

class CommentController extends Controller {

    public function rules() {
        return [
            'index' => '*',
            'save' => '*',
            'more' => '*',
            '*' => '@',
        ];
    }

    public function indexAction(int $id, $sort = 'created_at', $order = 'desc') {
        $comment_list = MicroRepository::comment()
            ->search('', 0, $id, 0, $sort, $order);
        return $this->show(compact('comment_list', 'id'));
    }

    public function moreAction(int $id, int $parent_id = 0, $sort = 'created_at', $order = 'desc') {
        $comment_list = MicroRepository::comment()
            ->search('', 0, $id, $parent_id, $sort, $order);
        if ($parent_id > 0) {
            return $this->show('rely', compact('comment_list', 'parent_id'));
        }
        return $this->show(compact('comment_list'));
    }

    public function saveAction($content,
                               int $micro_id,
                               int $parent_id = 0,
                               bool $is_forward = false) {
        try {
            $model = MicroRepository::commentSave($content,
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

    public function disagreeAction(int $id) {
        if (!request()->isAjax()) {
            return $this->redirect('./');
        }
        try {
            $model = MicroRepository::comment()->disagree($id);
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
            $model = MicroRepository::comment()->agree($id);
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
            $model = MicroRepository::deleteComment($id);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData($model);
    }

}