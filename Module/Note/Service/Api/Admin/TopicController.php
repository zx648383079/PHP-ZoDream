<?php
declare(strict_types=1);
namespace Module\Note\Service\Api\Admin;

use Module\Note\Domain\Repositories\NoteRepository;

class TopicController extends Controller {
    public function indexAction(string $keywords = '') {
        return $this->renderPage(
            NoteRepository::topicList($keywords)
        );
    }

    public function deleteAction(int $id) {
        try {
            NoteRepository::topicRemove($id, true);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}