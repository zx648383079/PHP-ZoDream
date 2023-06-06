<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Service\Api\Maker;

use Exception;
use Module\Game\GameMaker\Domain\Repositories\ItemRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class ItemController extends Controller {

    public function indexAction(int $project, string $keywords = '') {
        try {
            return $this->renderPage(ItemRepository::makerList($project, $keywords));
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'project_id' => 'required|int',
                'type' => 'required|int',
                'name' => 'required|string:0,255',
                'description' => 'required|string:0,255',
            ]);
            return $this->render(
                ItemRepository::makerSave($data)
            );
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $project, int $id) {
        try {
            ItemRepository::makerRemove($project, $id);
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}