<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Service\Api\Maker;


use Exception;
use Module\Game\GameMaker\Domain\Repositories\MapRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class MapController extends Controller {

    public function indexAction(int $project) {
        try {
            return $this->render(MapRepository::makerList($project));
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'project_id' => 'required|int',
                'area_id' => 'int',
                'name' => 'required|string:0,255',
                'description' => 'string:0,255',
                'south_id' => 'int',
                'east_id' => 'int',
                'north_id' => 'int',
                'west_id' => 'int',
                'x' => 'int',
                'y' => 'int',
            ]);
            return $this->render(
                MapRepository::makerSave($data)
            );
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function batchSaveAction(int $project, array $data) {
        try {
            MapRepository::makerBatchSave($project, $data);
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function deleteAction(int $project, int $id) {
        try {
            MapRepository::makerRemove($project, $id);
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}