<?php
namespace Module\Blog\Service\Api\Admin;

use Module\Blog\Domain\Repositories\CommentRepository;

class CommentController extends Controller {

    public function indexAction(int $blog_id = 0,
                                string $keywords = '', string $email = '', string $name = '') {
        return $this->renderPage(
            CommentRepository::commentList($blog_id, $keywords, $email, $name)
        );
    }

    public function deleteAction(int $id) {
        CommentRepository::remove($id);
        return $this->renderData(true);
    }
}