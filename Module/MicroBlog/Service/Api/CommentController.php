<?php
declare(strict_types=1);
namespace Module\MicroBlog\Service\Api;

use Module\MicroBlog\Domain\Model\CommentModel;
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

    public function indexAction($id, $parent_id = 0, $sort = 'created_at', $order = 'desc') {
        list($sort, $order) = CommentModel::checkSortOrder($sort, $order, ['created_at', 'id']);
        $comment_list = CommentModel::with('replies', 'user')
            ->where([
                'micro_id' => intval($id),
                'parent_id' => intval($parent_id)
            ])->orderBy($sort, $order)->page();
        return $this->renderPage($comment_list);
    }

    public function saveAction($content,
                               int $micro_id,
                               int $parent_id = 0,
                               bool $is_forward = false) {
        try {
            $model = MicroRepository::comment($content,
                $micro_id,
                $parent_id,
                $is_forward);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function disagreeAction($id) {
        try {
            $model = MicroRepository::disagree($id);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function agreeAction($id) {
        try {
            $model = MicroRepository::agree($id);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function deleteAction($id) {
        try {
            $model = MicroRepository::deleteComment($id);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }
}