<?php
declare(strict_types=1);
namespace Module\OnlineTV\Service\Api\Admin;


use Module\OnlineTV\Domain\Repositories\TVRepository;

class CommentController extends Controller {

    public function indexAction(int $res_id = 0,
                                string $keywords = '', int $user = 0) {
        return $this->renderPage(
            TVRepository::comment()->search($keywords, $user, $res_id)
        );
    }

    public function deleteAction(int $id) {
        TVRepository::comment()->remove($id);
        return $this->renderData(true);
    }
}