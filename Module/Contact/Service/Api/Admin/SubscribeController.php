<?php
declare(strict_types=1);
namespace Module\Contact\Service\Api\Admin;

use Module\Contact\Domain\Repositories\SubscribeRepository;

class SubscribeController extends Controller {
    public function indexAction(string $keywords = '') {
        return $this->renderPage(
            SubscribeRepository::getList($keywords)
        );
    }

    public function changeAction(int|array $id, int $status = 0) {
        try {
            SubscribeRepository::change($id, $status);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function deleteAction(int|array $id) {
        try {
            SubscribeRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}