<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Service\Api\Maker;

use Exception;
use Module\Game\GameMaker\Domain\Repositories\GradeRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class GradeController extends Controller {

    public function indexAction(int $project, string $keywords = '') {
        try {
            return $this->renderPage(GradeRepository::makerList($project, $keywords));
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'project_id' => 'required|int',
                'grade' => 'required|int',
                'name' => 'string:0,255',
                'exp' => 'string:0,20',
            ]);
            return $this->render(
                GradeRepository::makerSave($data)
            );
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $project, int $id) {
        try {
            GradeRepository::makerRemove($project, $id);
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function generateAction(int $project, Input $input) {
        try {
            $data = $input->validate([
                'begin' => 'required|int',
                'end' => 'required|int',
                'begin_exp' => 'required|int',
                'step_exp' => 'int',
                'step_type' => 'int',
            ]);
            GradeRepository::makerGenerate($project, $data);
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}