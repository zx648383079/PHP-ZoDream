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

    public function deleteAction(int $id) {
        try {
            ThreadRepository::manageRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}