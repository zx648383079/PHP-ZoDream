<?php
declare(strict_types=1);
namespace Module\Disk\Service\Api;

use Module\Disk\Domain\Repositories\TrashRepository;

class TrashController extends Controller {

    public function indexAction() {
        $data = TrashRepository::getList();
        return $this->renderData($data);
    }

    public function resetAction(int $id) {
        TrashRepository::reset($id);
        return $this->renderData(true);
    }

    public function deleteAction(int $id) {
        TrashRepository::remove($id);
        return $this->renderData(true);
    }

    public function clearAction() {
        TrashRepository::clear();
        return $this->renderData(true);
    }
}