<?php
namespace Module\Blog\Service\Api;

use Module\Blog\Domain\Model\BlogModel;
use Module\Blog\Domain\Model\CommentModel;
use Module\Blog\Domain\Repositories\CommentRepository;
use Zodream\Route\Controller\RestController;


class CommentController extends RestController {

    protected function rules() {
        return [
            'index' => '*',
            'save' => '*',
            '*' => '@',
        ];
    }

    public function indexAction($blog_id, $parent_id = 0, $is_hot = false, $sort = 'created_at', $order = 'desc') {
        $comment_list = CommentRepository::getList($blog_id, $parent_id, $is_hot, $sort, $order);
        return $this->renderPage($comment_list);
    }

    public function saveAction() {
        $data = app('request')->get('name,email,url,content,parent_id,blog_id');
        if (!BlogModel::canComment($data['blog_id'])) {
            return $this->renderFailure('不允许评论！');
        }
        if (!auth()->guest()) {
            $data['user_id'] = auth()->id();
            $data['name'] = auth()->user()->name;
        }
        $data['parent_id'] = intval($data['parent_id']);
        $last = CommentModel::where('blog_id', $data['blog_id'])->where('parent_id', $data['parent_id'])->orderBy('position desc')->one();
        $data['position'] = empty($last) ? 1 : ($last->position + 1);
        $comment = CommentModel::create($data);
        if (empty($comment)) {
            return $this->renderFailure('评论失败！');
        }
        BlogModel::where('id', $data['blog_id'])->updateOne('comment_count');
        return $this->render($comment->toArray());
    }

    public function disagreeAction($id) {
        $id = intval($id);
        if (!CommentModel::canAgree($id)) {
            return $this->renderFailure('一个用户只能操作一次！');
        }
        $comment = CommentModel::find($id);
        $comment->agreeThis(false);
        return $this->render($comment->toArray());
    }

    public function agreeAction($id) {
        $id = intval($id);
        if (!CommentModel::canAgree($id)) {
            return $this->jsonFailure('一个用户只能操作一次！');
        }
        $comment = CommentModel::find($id);
        $comment->agreeThis();
        return $this->render($comment->toArray());
    }

    public function reportAction($id) {

    }
}