<?php
declare(strict_types=1);
namespace Module\Blog\Service\Api;

use Module\Blog\Domain\Repositories\CommentRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class CommentController extends Controller {

    public function rules() {
        return [
            'index' => '*',
            'save' => '*',
            '*' => '@',
        ];
    }

    public function indexAction(int $blog_id, int $parent_id = 0, bool $is_hot = false,
                                string $sort = 'created_at', string $order = 'desc') {
        $comment_list = CommentRepository::getList($blog_id, $parent_id, $is_hot, $sort, $order);
        return $this->renderPage($comment_list);
    }

    public function saveAction(Request $request) {
        try {
            $data = $request->validate([
                'content' => 'required|string:0,255',
                'name' => 'string:0,30',
                'email' => 'string:0,50',
                'url' => 'string:0,50',
                'parent_id' => 'int',
                'blog_id' => 'required|int',
            ]);
            $comment = CommentRepository::create($data);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($comment->toArray());
    }

    public function disagreeAction(int $id) {
        try {
            $model = CommentRepository::disagree($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model->toArray());
    }

    public function agreeAction(int $id) {
        try {
            $model = CommentRepository::agree($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model->toArray());
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