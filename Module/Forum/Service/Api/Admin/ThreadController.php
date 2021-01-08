<?php
declare(strict_types=1);
namespace Module\Forum\Service\Api\Admin;

use Module\Forum\Domain\Repositories\ThreadRepository;

class ThreadController extends Controller {

    public function indexAction(string $keywords = '', int $forum_id = 0) {
        return $this->renderPage(
            ThreadRepository::getList($keywords, $forum_id)
        );
    }

    public function deleteAction(int $id) {
        try {
            ThreadRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}