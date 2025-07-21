<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Service\Api\Maker;

use Exception;
use Module\Game\GameMaker\Domain\Repositories\RecipeRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class RecipeController extends Controller {

    public function indexAction(int $project, string $keywords = '') {
        try {
            return $this->renderPage(RecipeRepository::makerList($project, $keywords));
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
                'icon' => 'string:0,255',
                'description' => 'string:0,255',
                'data' => 'string',
            ]);
            return $this->render(
                RecipeRepository::makerSave($data)
            );
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $project, int $id) {
        try {
            RecipeRepository::makerRemove($project, $id);
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}