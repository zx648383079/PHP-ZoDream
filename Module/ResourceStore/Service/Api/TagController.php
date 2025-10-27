<?php
declare(strict_types=1);
namespace Module\ResourceStore\Service\Api;

use Module\ResourceStore\Domain\Repositories\ResourceRepository;

class TagController extends Controller {

    public function indexAction(string $keywords = '') {
        return $this->renderPage(
            ResourceRepository::tag()->getList($keywords)
        );
    }

    public function allAction() {
        return $this->renderData(
            ResourceRepository::tag()->all()
        );
    }
}