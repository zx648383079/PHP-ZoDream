<?php
declare(strict_types=1);
namespace Module\MicroBlog\Service\Api;

use Module\MicroBlog\Domain\Repositories\CommentRepository;
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

    public function indexAction(int $id, int $parent_id = 0, string $sort = 'created_at', string $order = 'desc') {
        return $this->renderPage(
            CommentRepository::commentList($id, $parent_id, $sort, $order)
        );
    }

    public function saveAction(string $content,
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

    public function disagreeAction(int $id) {
        try {
            $model = CommentRepository::disagree($id);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function agreeAction(int $id) {
        try {
            $model = CommentRepository::agree($id);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function deleteAction(int $id) {
        try {
            $model = MicroRepository::deleteComment($id);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }
}