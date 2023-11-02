<?php
declare(strict_types=1);
namespace Module\Code\Service\Admin;

use Module\Code\Domain\Repositories\CodeRepository;

class CommentController extends Controller {

    public function indexAction(int $code_id = 0, string $keywords = '') {
        $items = CodeRepository::comment()->search($keywords, 0, $code_id);
        return $this->show(compact('items'));
    }

    public function deleteAction(int $id) {
        CodeRepository::comment()->remove($id);
        return $this->renderData([
            'url' => $this->getUrl('comment')
        ]);
    }
}