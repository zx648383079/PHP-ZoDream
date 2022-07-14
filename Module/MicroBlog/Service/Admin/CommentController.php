<?php
declare(strict_types=1);
namespace Module\MicroBlog\Service\Admin;

use Module\MicroBlog\Domain\Repositories\MicroRepository;

class CommentController extends Controller {

    public function indexAction(int $micro_id = 0, string $keywords = '') {
        $comment_list = MicroRepository::comment()->search($keywords, 0, $micro_id);
        return $this->show(compact('comment_list'));
    }

    public function deleteAction(int $id) {
        MicroRepository::comment()->remove($id);
        return $this->renderData([
            'url' => $this->getUrl('comment')
        ]);
    }
}