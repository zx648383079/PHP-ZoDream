<?php
declare(strict_types=1);
namespace Module\MessageService\Service\Api\Admin;

use Module\MessageService\Domain\Repositories\MessageRepository;

class LogController extends Controller {

    public function indexAction(int $status = 0, string $keywords = '') {
        return $this->renderPage(MessageRepository::logList($keywords, $status));
    }

    public function deleteAction(int|array $id) {
        try {
            MessageRepository::logRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
    public function clearAction() {
        try {
            MessageRepository::logClear();
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

}