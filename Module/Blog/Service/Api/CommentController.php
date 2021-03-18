<?php
namespace Module\Blog\Service\Api;

use Module\Blog\Domain\Model\CommentModel;
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

    public function indexAction(int $blog_id, int $parent_id = 0, $is_hot = false, $sort = 'created_at', $order = 'desc') {
        $comment_list = CommentRepository::getList($blog_id, $parent_id, $is_hot, $sort, $order);
        return $this->renderPage($comment_list);
    }

    public function saveAction(Request $request) {
        try {
            $comment = CommentRepository::create($request->get('name,email,url,content,parent_id,blog_id'));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($comment->toArray());
    }

    public function disagreeAction(int $id) {
        if (!CommentModel::canAgree($id)) {
            return $this->renderFailure('一个用户只能操作一次！');
        }
        $comment = CommentModel::find($id);
        $comment->agreeThis(false);
        return $this->render($comment->toArray());
    }

    public function agreeAction(int $id) {
        if (!CommentModel::canAgree($id)) {
            return $this->renderFailure('一个用户只能操作一次！');
        }
        $comment = CommentModel::find($id);
        $comment->agreeThis();
        return $this->render($comment->toArray());
    }

    public function reportAction(int $id) {

    }
}