<?php
declare(strict_types=1);
namespace Module\Video\Service\Api\Admin;

use Module\Video\Domain\Repositories\VideoRepository;

class CommentController extends Controller {

    public function indexAction(string $keywords = '', int $video = 0, int $user = 0) {
        return $this->renderPage(
            VideoRepository::comment()->search($keywords, $user, $video)
        );
    }

    public function deleteAction(int $id) {
        try {
            VideoRepository::comment()->remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}