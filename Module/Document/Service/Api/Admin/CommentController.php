<?php
declare(strict_types=1);
namespace Module\Document\Service\Api\Admin;

use Module\Document\Domain\Repositories\ProjectRepository;

class CommentController extends Controller {

    public function indexAction(string $keywords = '', int $target = 0, int $user = 0) {
        return $this->renderPage(
            ProjectRepository::comment()->search($keywords, $user, $target)
        );
    }

    public function deleteAction(int $id) {
        try {
            ProjectRepository::comment()->remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}