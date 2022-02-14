<?php
declare(strict_types=1);
namespace Module\Disk\Service\Api;

use Module\Disk\Domain\Repositories\ShareRepository;

class ShareController extends Controller {

    public function indexAction() {
        return $this->renderPage(
            ShareRepository::publicList()
        );
    }

    public function myAction() {
        return $this->renderPage(
            ShareRepository::myList()
        );
    }

    public function deleteAction(int $id) {
        ShareRepository::remove($id);
        return $this->renderData(true);
    }
}