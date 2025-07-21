<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Service\Api\Maker;

use Exception;
use Module\Game\GameMaker\Domain\Repositories\AchieveRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class AchieveController extends Controller {

    public function indexAction(int $project, string $keywords = '') {
        try {
            return $this->renderPage(AchieveRepository::makerList($project, $keywords));
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'project_id' => 'required|int',
                'name' => 'required|string:0,255',
                'description' => 'string:0,255',
                'icon' => 'required|string:0,255',
                'demand' => 'int',
            ]);
            return $this->render(
                AchieveRepository::makerSave($data)
            );
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $project, int $id) {
        try {
            AchieveRepository::makerRemove($project, $id);
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}