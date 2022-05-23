<?php
declare(strict_types=1);
namespace Module\AppStore\Service\Api\Admin;

use Module\AppStore\Domain\Repositories\AppRepository;

class TagController extends Controller {

    public function indexAction(string $keywords = '') {
        return $this->renderPage(
            AppRepository::tag()->getList($keywords)
        );
    }

    public function deleteAction(int $id) {
        AppRepository::tag()->remove($id);
        return $this->renderData(true);
    }

    public function allAction() {
        return $this->renderData(
            AppRepository::tag()->all()
        );
    }
}