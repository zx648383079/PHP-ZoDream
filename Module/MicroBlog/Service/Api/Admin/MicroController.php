<?php
declare(strict_types=1);
namespace Module\MicroBlog\Service\Api\Admin;

use Module\MicroBlog\Domain\Repositories\MicroRepository;

class MicroController extends Controller {
    public function indexAction(string $keywords = '', int $user = 0, int $topic = 0) {
        return $this->renderPage(
            MicroRepository::getList('new', $keywords, 0, $user, $topic)
        );
    }

    public function deleteAction(int $id) {
        try {
            MicroRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}