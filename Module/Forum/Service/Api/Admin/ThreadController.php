<?php
declare(strict_types=1);
namespace Module\Forum\Service\Api\Admin;

use Module\Forum\Domain\Repositories\ThreadRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

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

    public function postAction(int $thread, string $keywords = '', int $user = 0) {
        return $this->renderPage(
            ThreadRepository::managePostList($thread, $keywords, $user)
        );
    }

     public function postSaveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'required|int',
                'content' => 'required|string',
            ]);
            return $this->render(
                ThreadRepository::managePostSave($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function postDeleteAction(int|array $id) {
        try {
            ThreadRepository::managePostRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}