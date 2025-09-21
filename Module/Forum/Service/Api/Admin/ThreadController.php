<?php
declare(strict_types=1);
namespace Module\Forum\Service\Api\Admin;

use Module\Forum\Domain\Repositories\ThreadRepository;

class ThreadController extends Controller {

    public function indexAction(string $keywords = '', int $forum = 0) {
        return $this->renderPage(
            ThreadRepository::manageList($keywords, $forum)
        );
    }

    public function changeAction(int $id, int $status = 0) {
        try {
            return $this->render(ThreadRepository::manageChange($id, $status));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(array|int $id) {
        try {
            ThreadRepository::manageRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}