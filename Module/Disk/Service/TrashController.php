<?php
namespace Module\Disk\Service;

use Module\Disk\Domain\Model\DiskModel;
use Module\Disk\Domain\Repositories\TrashRepository;


/**
 * Created by PhpStorm.
 * User: ZoDream
 * Date: 2017/8/21
 * Time: 22:31
 */
class TrashController extends Controller {

    public function indexAction() {
        return $this->show();
    }

    public function listAction() {
        $data = TrashRepository::getList();
        return $this->renderData($data);
    }

    public function resetAction($id) {
        TrashRepository::reset($id);
        return $this->renderData(true);
    }

    public function deleteAction($id) {
        TrashRepository::remove($id);
        return $this->renderData(true);
    }

    public function clearAction() {
        TrashRepository::clear();
        return $this->renderData(true);
    }
}