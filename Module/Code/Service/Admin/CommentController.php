<?php
declare(strict_types=1);
namespace Module\Code\Service\Admin;

use Module\Code\Domain\Repositories\CodeRepository;

class CommentController extends Controller {

    public function indexAction(int $code_id = 0, string $keywords = '') {
        $comment_list = CodeRepository::comment()->search($keywords, 0, $code_id);
        return $this->show(compact('comment_list'));
    }

    public function deleteAction(int $id) {
        CodeRepository::comment()->remove($id);
        return $this->renderData([
            'url' => $this->getUrl('comment')
        ]);
    }
}