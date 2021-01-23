<?php
declare(strict_types=1);
namespace Module\Video\Service\Api;

use Module\Video\Domain\Repositories\MusicRepository;

class MusicController extends Controller {

    public function indexAction(string $keywords = '') {
        return $this->renderPage(
            MusicRepository::getList($keywords),
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                MusicRepository::get($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}