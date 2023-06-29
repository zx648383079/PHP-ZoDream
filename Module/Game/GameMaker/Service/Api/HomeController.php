<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Service\Api;
use Module\Game\GameMaker\Domain\Repositories\ProjectRepository;

class HomeController extends Controller {

    public function indexAction(string $keywords = '') {
        return $this->renderPage(ProjectRepository::getList($keywords));
    }
}