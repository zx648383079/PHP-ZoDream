<?php
namespace Module\Blog\Service\Admin;

use Module\Blog\Domain\Repositories\CommentRepository;

class CommentController extends Controller {

    public function indexAction(int $blog_id = 0, string $keywords = '', string $email = '',
                                string $name = '') {
        $items = CommentRepository::commentList($blog_id, $keywords, $email, $name);
        return $this->show(compact('items'));
    }

    public function deleteAction(int $id) {
        CommentRepository::remove($id);
        return $this->renderData([
            'url' => $this->getUrl('comment')
        ]);
    }
}