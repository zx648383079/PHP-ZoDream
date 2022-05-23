<?php
declare(strict_types=1);
namespace Module\ResourceStore\Service\Api\Admin;

use Module\ResourceStore\Domain\Repositories\ResourceRepository;

class CommentController extends Controller {

    public function indexAction(int $res_id = 0,
                                string $keywords = '', int $user = 0) {
        return $this->renderPage(
            ResourceRepository::comment()->search($keywords, $user, $res_id)
        );
    }

    public function deleteAction(int $id) {
        ResourceRepository::comment()->remove($id);
        return $this->renderData(true);
    }
}