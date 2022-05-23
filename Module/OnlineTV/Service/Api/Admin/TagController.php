<?php
declare(strict_types=1);
namespace Module\OnlineTV\Service\Api\Admin;

use Module\OnlineTV\Domain\Repositories\TVRepository;

class TagController extends Controller {

    public function indexAction(string $keywords = '') {
        return $this->renderPage(
            TVRepository::tag()->getList($keywords)
        );
    }

    public function deleteAction(int $id) {
        TVRepository::tag()->remove($id);
        return $this->renderData(true);
    }

    public function allAction() {
        return $this->renderData(
            TVRepository::tag()->all()
        );
    }
}