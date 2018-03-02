<?php
namespace Module\Blog\Service\Admin;

use Module\Blog\Domain\Model\CommentModel;

class CommentController extends Controller {

    public function indexAction() {
        $comment_list = CommentModel::with('user', 'blog')->order('id', 'desc')->page();
        return $this->show(compact('comment_list'));
    }

    public function deleteAction($id) {
        CommentModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('comment')
        ]);
    }
}