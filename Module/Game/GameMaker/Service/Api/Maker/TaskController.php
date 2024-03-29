<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Service\Api\Maker;

use Exception;
use Module\Game\GameMaker\Domain\Repositories\TaskRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class TaskController extends Controller {

    public function indexAction(int $project, string $keywords = '') {
        try {
            return $this->renderPage(TaskRepository::makerList($project, $keywords));
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'project_id' => 'required|int',
                'title' => 'required|string:0,255',
                'description' => 'string:0,255',
                'gift' => 'string:0,255',
                'before' => 'string:0,255',
                'type' => 'int:0,127',
            ]);
            return $this->render(
                TaskRepository::makerSave($data)
            );
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $project, int $id) {
        try {
            TaskRepository::makerRemove($project, $id);
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

}