<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Service\Api\Maker;

use Exception;
use Module\Game\GameMaker\Domain\Repositories\SkillRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class SkillController extends Controller {

    public function indexAction(int $project, string $keywords = '') {
        try {
            return $this->renderPage(SkillRepository::makerList($project, $keywords));
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
                'description' => 'required|string:0,255',
                'gift' => 'required|string:0,255',
                'before' => 'required|string:0,255',
            ]);
            return $this->render(
                SkillRepository::makerSave($data)
            );
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $project, int $id) {
        try {
            SkillRepository::makerRemove($project, $id);
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

}