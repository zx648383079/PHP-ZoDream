<?php
declare(strict_types=1);
namespace Module\Video\Service\Api\Admin;

use Module\Video\Domain\Repositories\CommentRepository;

class CommentController extends Controller {

    public function indexAction(string $keywords = '', int $video = 0, int $user = 0) {
        return $this->renderPage(
            CommentRepository::getList($keywords, $video, $user)
        );
    }

    public function deleteAction(int $id) {
        try {
            CommentRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}