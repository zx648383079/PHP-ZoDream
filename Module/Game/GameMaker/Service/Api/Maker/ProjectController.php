<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Service\Api\Maker;

use Exception;
use Module\Game\GameMaker\Domain\Repositories\ProjectRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class ProjectController extends Controller {

    public function indexAction(string $keywords = '') {
        return $this->renderPage(
            ProjectRepository::selfList($keywords)
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                ProjectRepository::selfGet($id)
            );
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'name' => 'required|string:0,255',
                'logo' => 'string:0,255',
                'description' => 'string:0,255',
                'status' => 'int:0,127',
            ]);
            return $this->render(
                ProjectRepository::selfSave($data)
            );
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            ProjectRepository::selfRemove($id);
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function statisticsAction(int $project) {
        try {
            return $this->render(
                ProjectRepository::selfStatistics($project)
            );
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}