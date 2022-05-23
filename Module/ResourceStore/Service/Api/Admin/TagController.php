<?php
declare(strict_types=1);
namespace Module\ResourceStore\Service\Api\Admin;

use Module\ResourceStore\Domain\Repositories\ResourceRepository;

class TagController extends Controller {

    public function indexAction(string $keywords = '') {
        return $this->renderPage(
            ResourceRepository::tag()->getList($keywords)
        );
    }

    public function deleteAction(int $id) {
        ResourceRepository::tag()->remove($id);
        return $this->renderData(true);
    }

    public function allAction() {
        return $this->renderData(
            ResourceRepository::tag()->all()
        );
    }
}