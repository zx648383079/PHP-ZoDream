<?php
declare(strict_types=1);
namespace Module\Video\Service\Api\Admin;

use Module\Video\Domain\Repositories\VideoRepository;

class VideoController extends Controller {

    public function indexAction(string $keywords = '', int $user = 0, int $music = 0) {
        return $this->renderPage(
            VideoRepository::getList($keywords, $user, $music)
        );
    }

    public function changeAction(int $id, int $status) {
        try {
            $this->render(
                VideoRepository::changeStatus($id, $status)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            VideoRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}