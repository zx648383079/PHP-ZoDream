<?php
declare(strict_types=1);
namespace Module\AppStore\Service\Api\Admin;


use Module\AppStore\Domain\Repositories\AppRepository;

class CommentController extends Controller {

    public function indexAction(int $res_id = 0,
                                string $keywords = '', int $user = 0) {
        return $this->renderPage(
            AppRepository::comment()->search($keywords, $user, $res_id)
        );
    }

    public function deleteAction(int $id) {
        AppRepository::comment()->remove($id);
        return $this->renderData(true);
    }
}