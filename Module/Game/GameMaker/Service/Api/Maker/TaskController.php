<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Service\Api\Maker;

use Exception;
use Module\Game\GameMaker\Domain\Repositories\TaskRepository;

class TaskController extends Controller {

    public function indexAction(int $project, string $keywords = '') {
        try {
            return $this->renderPage(TaskRepository::makerList($project, $keywords));
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

}