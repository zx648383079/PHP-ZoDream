<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Service\Api\Maker;


use Exception;
use Module\Game\GameMaker\Domain\Repositories\MapRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class MapController extends Controller {

    public function indexAction(int $project) {
        try {
            return $this->renderPage(MapRepository::makerList($project));
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
    public function detailAction(int $project, int $id) {
        try {
            return $this->render(
                //
            );
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([

            ]);
            return $this->render(
                //
            );
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {

        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}