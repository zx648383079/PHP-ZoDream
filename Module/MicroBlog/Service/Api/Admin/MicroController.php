<?php
declare(strict_types=1);
namespace Module\MicroBlog\Service\Api\Admin;

use Module\MicroBlog\Domain\Repositories\MicroRepository;

class MicroController extends Controller {
    public function indexAction(string $keywords = '', int $user = 0, int $topic = 0) {
        return $this->renderPage(
            MicroRepository::manageList($keywords, 0, $user, $topic)
        );
    }

    public function changeAction(int $id, int $status = 0) {
        try {
            return $this->render(MicroRepository::manageChange($id, $status));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            MicroRepository::manageRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}