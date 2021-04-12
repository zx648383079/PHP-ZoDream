<?php
declare(strict_types=1);
namespace Module\Forum\Service\Api;

use Module\Forum\Domain\Repositories\ForumRepository;
use Module\Forum\Domain\Repositories\ThreadRepository;

class HomeController extends Controller {

    public function indexAction(int $parent_id = 0) {
        return $this->renderData(
            ForumRepository::children($parent_id)
        );
    }

    public function detailAction(int $id, bool $full = true) {
        try {
            return $this->render(
                ForumRepository::getFull($id, $full)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function suggestionAction(string $keywords = '') {
        return $this->renderData(
            ThreadRepository::suggestion($keywords)
        );
    }
}