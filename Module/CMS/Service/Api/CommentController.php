<?php
declare(strict_types=1);
namespace Module\CMS\Service\Api;

class CommentController extends Controller {
    public function rules() {
        return [
            'index' => '*',
            'save' => '*',
            'more' => '*',
            '*' => '@',
        ];
    }

    public function indexAction(int $article, int|string $category, int|string $model,
                                int $parent_id = 0, string $sort = 'created_at', string $order = 'desc') {
        return $this->renderPage(
            CommentRepository::commentList($id, $parent_id, $sort, $order)
        );
    }

    public function createAction(string $content,
                               int $article, int|string $category, int|string $model,
                               int $parent_id = 0) {
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

    public function disagreeAction(int $id, int $article, int|string $category, int|string $model) {
        try {
            $model = MicroRepository::disagree($id);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function agreeAction(int $id, int $article, int|string $category, int|string $model) {
        try {
            $model = MicroRepository::agree($id);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function deleteAction(int $id, int $article, int|string $category, int|string $model) {
        try {
            $model = MicroRepository::deleteComment($id);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }
}