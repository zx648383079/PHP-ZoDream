<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Service\Api\Maker;


use Exception;
use Module\Game\GameMaker\Domain\Repositories\MapRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class MapController extends Controller {

    public function indexAction(int $project, int $area = 0, string $keywords = '') {
        try {
            return $this->renderPage(MapRepository::makerList($project, $area, $keywords));
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function allAction(int $project) {
        try {
            return $this->render(MapRepository::makerAll($project));
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

    public function areaAction(int $project, int $parent = 0, string $keywords = '') {
        try {
            return $this->renderPage(
                MapRepository::makerAreaList($project, $parent, $keywords)
            );
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function areaDetailAction(int $project, int $id) {
        try {
            return $this->render(
                MapRepository::makerAreaGet($project, $id)
            );
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function areaSaveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'project_id' => 'required|int',
                'name' => 'required|string:0,255',
                'parent_id' => 'int',
                'x' => 'int',
                'y' => 'int',
                'width' => 'int',
                'height' => 'int',
            ]);
            return $this->render(
                MapRepository::makerAreaSave($data)
            );
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function areaDeleteAction(int $project, int $id) {
        try {
            MapRepository::makerAreaRemove($project, $id);
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function itemAction(int $project, int $map) {
        try {
            return $this->renderPage(
                MapRepository::makerItemList($project, $map)
            );
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function itemSaveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'project_id' => 'required|int',
                'map_id' => 'required|int',
                'item_type' => 'int',
                'item_id' => 'required|int',
                'refresh_time' => 'int',
                'amount' => 'int',
            ]);
            return $this->render(
                MapRepository::makerItemSave($data)
            );
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function itemDeleteAction(int $project, int $id) {
        try {
            MapRepository::makerItemRemove($project, $id);
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}